<?php
/**
 * AdminController
 * @var ommu\event\controllers\registered\AdminController
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
	 * @inheritdoc
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

		$this->view->title = Yii::t('app', 'Event Registereds');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new EventRegistered model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new EventRegistered();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Registereds success created.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create Event Registereds');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing EventRegistered model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Registereds success updated.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update Event Registereds: {id}', ['id' => $model->id]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single EventRegistered model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Registereds: {id}', ['id' => $model->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventRegistered model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Event Registereds success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the EventRegistered model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventRegistered the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = EventRegistered::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
