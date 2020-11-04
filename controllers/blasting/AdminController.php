<?php
/**
 * AdminController
 * @var ommu\event\controllers\blasting\AdminController
 * @var $model ommu\event\models\EventBlastings
 *
 * AdminController implements the CRUD actions for EventBlastings model.
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
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-event
 *
 */
 
namespace ommu\event\controllers\blasting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\event\models\EventBlastings;
use ommu\event\models\search\EventBlastings as EventBlastingsSearch;
use ommu\event\models\EventBlastingItem;
use app\models\Users;
use app\modules\blasting\models\BlastingFilter;
use yii\helpers\Url;

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
	 * Lists all EventBlastings models.
	 * @return mixed
	 */
	public function actionManage()
	{
		// Create Blasting
		$model = new EventBlastings();

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
            if ($model->save()) {
				//return $this->redirect(['view', 'id' => $model->blast_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blastings success created.'));
				return $this->redirect(['index', 'event_id' => $model->event_id, 'filter_id' => $model->filter_id, 'blast_id' => $model->blast_id]);
			}
		}
		// --------------------------

		// Index Blasting
		$searchModel = new EventBlastingsSearch();
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

		$this->view->title = Yii::t('app', 'Event Blastings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new EventBlastings model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new EventBlastings();

        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
            if ($model->save()) {
				//return $this->redirect(['view', 'id' => $model->blast_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blastings success created.'));
				return $this->redirect(['index']);
			}
		}

		$this->view->title = Yii::t('app', 'Create Event Blastings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	public function actionBlast($blast_id)
	{
		$blast = EventBlastings::findOne($blast_id);
		
		// menyimpan blasting item berdasarkan user yang dipilih
        if (Yii::$app->request->post('user_list') != null) {
			foreach (Yii::$app->request->post('user_list') as $item) {

				$blasting_item = EventBlastingItem::find()->where(['blast_id' => $blast_id, 'user_id' => $item])->one();
                if ($blasting_item == null) {
					$blasting_item = new EventBlastingItem();
					$new = 1;
				} else {
					$new = 0;
				}
				$blasting_item->blast_id = $blast_id;
				$blasting_item->user_id = $item;

				// price
                if ($blast->event->price == 0) {
					$price = 'Free';
				} else {
					$price = $blast->event->price;
				}

				// jika new kirim email
                if ($new == 1) {
                    if (Yii::$app->mailer->compose()
						->setFrom('emailasale@gmail.com')
						->setTo('emailtujuan@gmail.com')
						->setSubject('Informasi Pendaftaran Training Online')
						->setTextBody('Plain text content')
						// ->setHtmlBody('<b>Event '.$blast->event->title.'</b><br>User : '.$item.'<br><a href="'.Url::to('@web/event/site/click?blast_id='.$blast->blast_id.'', true).'">Event</a>')
						->setHtmlBody('<b>Event : '.$blast->event->title.'</b><br>Location : '.$blast->event->location->zoneCity->city_name.' - '.$blast->event->location->zoneCity->province->province_name.'<br>Introduction : '.$blast->event->introduction.'<br>Price : '.$price.'</b><br><a href="'.Url::to('@web/event/site/click?blast_id='.$blast->blast_id.'', true).'">Event</a>')
						->send()) {

						$blasting_item->save(false);
					}
				}
			}

			// update event blast user
			$blasted_user = EventBlastingItem::find()->where(['blast_id' => $blast_id])->count();

			$blast->blast_with = 'email';
			$blast->users = $blasted_user;
			$blast->save(false);

			Yii::$app->session->setFlash('blast_success', Yii::t('app', 'Event Blastings success Blasted.'));
			// return $this->redirect(['index']);
			return $this->redirect(['index', 'event_id' => $blast->event_id, 'filter_id' => $blast->filter_id, 'blast_id' => $blast->blast_id]);
		} else {
			Yii::$app->session->setFlash('blast_error', Yii::t('app', 'Tidak ada user yang dipilih'));
			// return $this->redirect(['index']);
			return $this->redirect(['index', 'event_id' => $blast->event_id, 'filter_id' => $blast->filter_id, 'blast_id' => $blast->blast_id]);
		}
	}

	public function actionBlastAll($blast_id)
	{
		$blast = EventBlastings::findOne($blast_id);
		$filter = BlastingFilter::findOne($blast->filter_id);
		$filter_gender = unserialize($filter->filter_value);

		// find user yang sesuai kondisi
		$query = Users::find()
						->select('ommu_users.user_id')
						->leftJoin('ommu_cv_bio', '`ommu_users`.`user_id` = `ommu_cv_bio`.`user_id`')
						->where(['in', 'ommu_cv_bio.gender', $filter_gender['gender']])
						->all();

		$user_list = [];
		$i = 0;
		foreach ($query as $value) {
			$user_list[] = $value['user_id'];
			$i++;
		}

		// menyimpan blasting item semua user yang sesuai kondisi
		foreach ($user_list as $user) {
			$blasting_item = EventBlastingItem::find()->where(['blast_id' => $blast_id, 'user_id' => $user])->one();
            if ($blasting_item == null)
				$blasting_item = new EventBlastingItem();
			$blasting_item->blast_id = $blast_id;
			$blasting_item->user_id = $user;

			// price
            if ($blast->event->price == 0) {
				$price = 'Free';
			} else {
				$price = $blast->event->price;
			}

			// send email to notify that registrations success
            if (Yii::$app->mailer->compose()
					->setFrom('emailasale@gmail.com')
					->setTo('emailtujuan@gmail.com')
					->setSubject('Informasi Pendaftaran Training Online')
					->setTextBody('Plain text content')
					// ->setHtmlBody('<b>Blasted Event '.$blast->event->title.'</b><br>User : '.$user.'<br><a href="'.Url::to('@web/event/site/click?blast_id='.$blast->blast_id.'', true).'">Event</a>')
					->setHtmlBody('<b>Event : '.$blast->event->title.'</b><br>Location : '.$blast->event->location->zoneCity->city_name.' - '.$blast->event->location->zoneCity->province->province_name.'<br>Introduction : '.$blast->event->introduction.'<br>Price : '.$price.'</b><br><a href="'.Url::to('@web/event/site/click?blast_id='.$blast->blast_id.'', true).'">Event</a>')
					->send()) {
			
			$blasting_item->save(false);

			}
		}

		// update event blast user
		$blasted_user = EventBlastingItem::find()->where(['blast_id' => $blast_id])->count();
		$blast = EventBlastings::findOne($blast_id);
		$blast->blast_with = 'email';
		$blast->users = $blasted_user;
		$blast->save(false);

		Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blastings success Blasted.'));
		return $this->redirect(['index']);
	}

	/**
	 * Updates an existing EventBlastings model.
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
				//return $this->redirect(['view', 'id' => $model->blast_id]);
				Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blastings success updated.'));
				return $this->redirect(['index']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
			}
		}

		$this->view->title = Yii::t('app', 'Update Event Blastings: {blast_id}', ['blast_id' => $model->blast_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single EventBlastings model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View Event Blastings: {blast_id}', ['blast_id' => $model->blast_id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing EventBlastings model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Event Blastings success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the EventBlastings model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return EventBlastings the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = EventBlastings::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
