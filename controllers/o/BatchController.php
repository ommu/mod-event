<?php
/**
 * BatchController
 * @var ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 *
 * BatchController implements the CRUD actions for EventBatch model.
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
 * @created date 28 November 2017, 09:40 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers\o;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\EventBatch;
use ommu\event\models\search\EventBatch as EventBatchSearch;
use ommu\event\models\Events;
use ommu\event\models\EventSpeaker;
use ommu\event\models\EventNotification;

class BatchController extends Controller
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
	 * Lists all EventBatch models.
	 * @return mixed
	 */
	public function actionManage()
	{
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

		$this->view->title = Yii::t('app', 'Event Batches');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new EventBatch model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new EventBatch();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->batch_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Batch success created.'));
			return $this->redirect(['index']);

		} else {
			
			// cek jika tidak ada event
			$events = "";
			$title = Yii::t('app', 'Create Batch');
			if (($event = Yii::$app->request->get('event')) != null) {
				$events = Events::findOne($event);
				$title = Yii::t('app', 'Create Batch: {title}', ['title'=>$events->title]);
			}

			$this->view->title = $title;
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
				'events' => $events,
			]);
		}
	}

	/**
	 * Updates an existing EventBatch model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->batch_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Batch success updated.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update Event Batch: {batch_name}', ['batch_name' => $model->batch_name]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single EventBatch model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Batch: {batch_name}', ['batch_name' => $model->batch_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventBatch model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		if (EventSpeaker::find()->where(['batch_id' => $id])->andWhere(['not', 'publish=2'])->one() != null) {
			Yii::$app->session->setFlash('error', Yii::t('app', 'Batch cannot be deleted. Batch is still used in an Adviser.'));
			return $this->redirect(['index']);
		}

		if (EventNotification::find()->where(['batch_id' => $id])->one() != null) {
			Yii::$app->session->setFlash('error', Yii::t('app', 'Batch cannot be deleted. Batch is still used in a Notification.'));
			return $this->redirect(['index']);
		}

		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->batch_id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Batch success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Publish/Unpublish an existing EventBatch model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Batch success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the EventBatch model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventBatch the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = EventBatch::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
