<?php
/**
 * AdminController
 * @var ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 *
 * AdminController implements the CRUD actions for Events model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\Events;
use ommu\event\models\search\Events as EventsSearch;
use yii\web\UploadedFile;
use ommu\event\models\search\EventBatch as EventBatchSearch;
use ommu\event\models\EventBatch;
use ommu\event\models\EventBlastings;

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
					'publish' => ['POST'],
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
	 * Lists all Events models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventsSearch();
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

		$this->view->title = Yii::t('app', 'Events');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new Events model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Events();
		$model->scenario = 'formCreate';

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			// upload cover
			$model->cover_filename = UploadedFile::getInstance($model, 'cover_filename');
			// upload banner
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Events success created.'));
				return $this->redirect(['update', 'event' => $model->event_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Events');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Events model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($event)
	{
		$model = $this->findModel($event);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			
			// upload cover
			$model->cover_filename = UploadedFile::getInstance($model, 'cover_filename');
			if(!($model->cover_filename instanceof UploadedFile)) {
				$model->cover_filename = $model->old_cover;
			}

			// upload banner
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
			if(!($model->banner_filename instanceof UploadedFile)) {
				$model->banner_filename = $model->old_banner;
			}

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Events success updated.'));
				return $this->redirect(['index']);
			}
		}
		// Batch
		$searchModel = new EventBatchSearch();
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

		// ------------------------------------
		$this->view->title = Yii::t('app', 'Update Events: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Displays a single Events model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Events: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Events model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		if (EventBatch::find()->where(['event_id' => $id])->andWhere(['not', 'publish=2'])->one() != null) {
			Yii::$app->session->setFlash('error', Yii::t('app', 'Event cannot be deleted. Event is still used in a Batch.'));
			return $this->redirect(['index']);
		}

		if (EventBlastings::find()->where(['event_id' => $id])->one() != null) {
			Yii::$app->session->setFlash('error', Yii::t('app', 'Event cannot be deleted. Event is still used in a Blasting.'));
			return $this->redirect(['index']);
		}

		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->event_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Events success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Publish/Unpublish an existing Events model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if ($model->save(false, ['publish'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Events success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the Events model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Events the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Events::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
