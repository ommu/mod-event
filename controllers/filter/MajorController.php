<?php
/**
 * MajorController
 * @var ommu\event\controllers\filter\MajorController
 * @var $model ommu\event\models\EventFilterMajor
 *
 * MajorController implements the CRUD actions for EventFilterMajor model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:22 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers\filter;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\EventFilterMajor;
use ommu\event\models\search\EventFilterMajor as EventFilterMajorSearch;

class MajorController extends Controller
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
	 * Lists all EventFilterMajor models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventFilterMajorSearch();
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

		$this->view->title = Yii::t('app', 'Event Filter Majors');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Displays a single EventFilterMajor model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Filter Major: {id}', ['id' => $model->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventFilterMajor model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Event Filter Major success deleted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Finds the EventFilterMajor model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventFilterMajor the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = EventFilterMajor::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
