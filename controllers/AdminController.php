<?php
/**
 * AdminController
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 *
 * AdminController implements the CRUD actions for Events model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	Filter
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *	Registered
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @modified date 24 June 2019, 10:28 WIB
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

class AdminController extends Controller
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
					'publish' => ['POST'],
					'registered' => ['POST'],
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

		if(($category = Yii::$app->request->get('category')) != null)
			$category = \ommu\event\models\EventCategory::findOne($category);

		$this->view->title = Yii::t('app', 'Events');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'category' => $category,
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

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->cover_filename = UploadedFile::getInstance($model, 'cover_filename');
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event success created.'));
				return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Event');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Events model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->cover_filename = UploadedFile::getInstance($model, 'cover_filename');
			$model->banner_filename = UploadedFile::getInstance($model, 'banner_filename');
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event success updated.'));
				return $this->redirect(['update', 'id' => $model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->subMenu = $this->module->params['event_submenu'];
		$this->view->title = Yii::t('app', 'Update Event: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Events model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionFilter($id)
	{
		$model = $this->findModel($id);
		$model->scenario = Events::SCENARIO_FILTER;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event filter success updated.'));
				return $this->redirect(['filter', 'id' => $model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->subMenu = $this->module->params['event_submenu'];
		$this->view->title = Yii::t('app', 'Update Filter: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_filter', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Events model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->subMenu = $this->module->params['event_submenu'];
		$this->view->title = Yii::t('app', 'Detail Event: {title}', ['title' => $model->title]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Events model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event success deleted.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionPublish an existing Events model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionRegistered an existing Events model.
	 * If registered-enable is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionRegistered($id)
	{
		$model = $this->findModel($id);
		$replace = $model->registered_enable == 1 ? 0 : 1;
		$model->registered_enable = $replace;
		
		if($model->save(false, ['registered_enable','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Event success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the Events model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Events the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Events::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}