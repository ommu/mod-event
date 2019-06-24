<?php
/**
 * SpeakerController
 * @var ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
 *
 * SpeakerController implements the CRUD actions for EventSpeaker model.
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
 * @created date 28 November 2017, 11:43 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers\o;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\EventSpeaker;
use ommu\event\models\EventBatch;
use ommu\event\models\search\EventSpeaker as EventSpeakerSearch;

class SpeakerController extends Controller
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
	 * Lists all EventSpeaker models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventSpeakerSearch();
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

		$this->view->title = Yii::t('app', 'Event Advisers');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new EventSpeaker model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new EventSpeaker();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Adviser success created.'));
			return $this->redirect(['index']);

		} else {
			if (($batch = Yii::$app->request->get('batch')) != null) {
				$batch = EventBatch::findOne($batch);
			} else {
				$batch = "";
			}
			$this->view->title = Yii::t('app', 'Create Event Adviser');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
				'batch' => $batch,
			]);
		}
	}

	/**
	 * Updates an existing EventSpeaker model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Adviser success updated.'));
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update Event Adviser: {speaker_name}', ['speaker_name' => $model->speaker_name]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single EventSpeaker model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Adviser: {speaker_name}', ['speaker_name' => $model->speaker_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventSpeaker model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->id]);
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Adviser success deleted.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Publish/Unpublish an existing EventSpeaker model.
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
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event Adviser success updated.'));
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the EventSpeaker model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventSpeaker the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = EventSpeaker::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
