<?php
/**
 * UserBannedController
 * @var ommu\event\controllers\UserBannedController
 * @var $model ommu\event\models\EventUserBanned
 *
 * UserBannedController implements the CRUD actions for EventUserBanned model.
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
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\event\models\EventUserBanned;
use ommu\event\models\search\EventUserBanned as EventUserBannedSearch;

class UserBannedController extends Controller
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
	 * Lists all EventUserBanned models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventUserBannedSearch();
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

		$this->view->title = Yii::t('app', 'Event User Banneds');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new EventUserBanned model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new EventUserBanned();
		$model->scenario = 'createForm';

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				//return $this->redirect(['view', 'id' => $model->banned_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event User Banned success created.'));
				return $this->redirect(['index']);
			} 
		}

		$this->view->title = Yii::t('app', 'Create Event User Banned');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing EventUserBanned model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'unbannedForm';

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				//return $this->redirect(['view', 'id' => $model->banned_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event User Banned success updated.'));
				return $this->redirect(['index']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Event User Banned: {banned_id}', ['banned_id' => $model->banned_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}


	/**
	 * Updates an existing EventUserBanned model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUnbanned($id)
	{
		$model = $this->findModel($id);
		
		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				//return $this->redirect(['view', 'id' => $model->banned_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event User Banned success updated.'));
				return $this->redirect(['index']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Unbanned Event User Banned: {banned_id}', ['banned_id' => $model->banned_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_unbanned', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single EventUserBanned model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event User Banned: {banned_id}', ['banned_id' => $model->banned_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventUserBanned model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Event User Banned success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the EventUserBanned model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventUserBanned the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = EventUserBanned::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
