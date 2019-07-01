<?php
/**
 * AdminController
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\EventRegistered
 *
 * AdminController implements the CRUD actions for EventRegistered model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\controllers\registered;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\EventRegistered;
use ommu\event\models\search\EventRegistered as EventRegisteredSearch;
use ommu\event\models\EventRegisteredFinance;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id'))
			$this->subMenu = $this->module->params['event_submenu'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all EventRegistered models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventRegisteredSearch();
		if(($id = Yii::$app->request->get('id')) != null)
			$searchModel = new EventRegisteredSearch(['event_id'=>$id]);
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if(($event = Yii::$app->request->get('event')) != null || ($event = Yii::$app->request->get('id')) != null)
			$event = \ommu\event\models\Events::findOne($event);

		$this->view->title = Yii::t('app', 'Registereds');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'event' => $event,
		]);
	}

	/**
	 * Creates a new EventRegistered model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(($id = Yii::$app->request->get('id')) == null)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new EventRegistered(['event_id'=>$id]);
		$this->subMenuParam = $model->event_id;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event registered success created.'));
				return $this->redirect(['manage', 'id'=>$model->event_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Registered');
		if($id)
			$this->view->title = Yii::t('app', 'Create Registered: {title}', ['title' => $model->event->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing EventRegistered model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$finance = EventRegisteredFinance::findOne($model->id);
		if($finance == null)
			$finance = new EventRegisteredFinance(['registered_id'=>$model->id]);
		$this->subMenuParam = $model->event_id;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$finance->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			$isValid = $model->validate();
			$isValid = $finance->validate() && $isValid;

			if($isValid) {
				if($model->save() && $finance->save()) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Event registered success updated.'));
					return $this->redirect(['manage', 'id'=>$model->event_id]);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Registered: {event-id}', ['event-id' => $model->event->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'finance' => $finance,
		]);
	}

	/**
	 * Displays a single EventRegistered model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->event_id;

		$this->view->title = Yii::t('app', 'Detail Registered: {event-id}', ['event-id' => $model->event->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventRegistered model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Event registered success deleted.'));
		return $this->redirect(['manage', 'id'=>$model->event_id]);
	}

	/**
	 * Finds the EventRegistered model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return EventRegistered the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = EventRegistered::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}