<?php
/**
 * HistoryController
 * @var ommu\event\controllers\blasting\HistoryController
 * @var $model ommu\event\models\EventBlastingHistory
 *
 * HistoryController implements the CRUD actions for EventBlastingHistory model.
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
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers\blasting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\event\models\EventBlastingHistory;
use ommu\event\models\search\EventBlastingHistory as EventBlastingHistorySearch;

class HistoryController extends Controller
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
	 * Lists all EventBlastingHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new EventBlastingHistorySearch();
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

		$this->view->title = Yii::t('app', 'Event Blasting Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new EventBlastingHistory model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new EventBlastingHistory();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
				//return $this->redirect(['view', 'id' => $model->id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blasting History success created.'));
                return $this->redirect(['index']);
			}
		}

		$this->view->title = Yii::t('app', 'Create Event Blasting History');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing EventBlastingHistory model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
				//return $this->redirect(['view', 'id' => $model->id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blasting History success updated.'));
                return $this->redirect(['index']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Event Blasting History: {id}', ['id' => $model->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single EventBlastingHistory model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Blasting History: {id}', ['id' => $model->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing EventBlastingHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blasting History success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the EventBlastingHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventBlastingHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = EventBlastingHistory::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
