<?php
/**
 * UniversityController
 * @var $this ommu\event\controllers\filter\UniversityController
 * @var $model ommu\event\models\EventFilterUniversity
 *
 * UniversityController implements the CRUD actions for EventFilterUniversity model.
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
 * @created date 28 November 2017, 09:25 WIB
 * @modified date 24 June 2019, 20:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\controllers\filter;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\event\models\EventFilterUniversity;
use ommu\event\models\search\EventFilterUniversity as EventFilterUniversitySearch;

class UniversityController extends Controller
{
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
	 * Lists all EventFilterUniversity models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new EventFilterUniversitySearch();
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

		if(($event = Yii::$app->request->get('event')) != null)
			$event = \ommu\event\models\Events::findOne($event);
		if(($university = Yii::$app->request->get('university')) != null)
			$university = \ommu\ipedia\models\IpediaUniversities::findOne($university);

		$this->view->title = Yii::t('app', 'Filter Universities');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'event' => $event,
			'university' => $university,
		]);
	}

	/**
	 * Displays a single EventFilterUniversity model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Filter University: {event-id}', ['event-id' => $model->event->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventFilterUniversity model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Event filter university success deleted.'));
		return $this->redirect(['manage']);
	}

	/**
	 * Finds the EventFilterUniversity model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return EventFilterUniversity the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = EventFilterUniversity::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}