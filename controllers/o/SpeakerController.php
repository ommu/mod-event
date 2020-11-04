<?php
/**
 * SpeakerController
 * @var $this ommu\event\controllers\o\SpeakerController
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
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 11:43 WIB
 * @modified date 26 June 2019, 22:56 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\controllers\o;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\event\models\EventSpeaker;
use ommu\event\models\search\EventSpeaker as EventSpeakerSearch;

class SpeakerController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

        if (Yii::$app->request->get('id')) {
            $this->subMenu = $this->module->params['event_submenu'];
        }
	}

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
        if (($id = Yii::$app->request->get('id')) != null) {
            $searchModel = new EventSpeakerSearch(['batch_id'=>$id]);
        }
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($batch = Yii::$app->request->get('batch')) != null || ($batch = Yii::$app->request->get('id')) != null) {
			$batch = \ommu\event\models\EventBatch::findOne($batch);
			$this->subMenuParam = $batch->event_id;
		}

		$this->view->title = Yii::t('app', 'Speakers');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'batch' => $batch,
		]);
	}

	/**
	 * Creates a new EventSpeaker model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        if (($id = Yii::$app->request->get('id')) == null) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

		$model = new EventSpeaker(['batch_id'=>$id]);
		$this->subMenuParam = $model->batch->event_id;

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event speaker success created.'));
				return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'id'=>$model->batch_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->view->title = Yii::t('app', 'Create Speaker');
        if ($id) {
            $this->view->title = Yii::t('app', 'Create Speaker: {batch-name}', ['batch-name' => $model->batch->batch_name]);
        }
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing EventSpeaker model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->batch->event_id;

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event speaker success updated.'));
				return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'id'=>$model->batch_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->view->title = Yii::t('app', 'Update Speaker: {speaker-name}', ['speaker-name' => $model->speaker_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single EventSpeaker model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->batch->event_id;

		$this->view->title = Yii::t('app', 'Detail Speaker: {speaker-name}', ['speaker-name' => $model->speaker_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventSpeaker model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event speaker success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'id'=>$model->batch_id]);
		}
	}

	/**
	 * actionPublish an existing EventSpeaker model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event speaker success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'id'=>$model->batch_id]);
		}
	}

	/**
	 * Finds the EventSpeaker model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return EventSpeaker the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = EventSpeaker::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}