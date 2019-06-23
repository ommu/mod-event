<?php
/**
 * EventNotification

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:40 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_notification".
 *
 * The followings are the available columns in table "ommu_event_notification":
 * @property string $id
 * @property integer $status
 * @property string $batch_id
 * @property string $notified_date
 * @property string $notified_id
 * @property integer $users
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property EventBatch $batch
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class EventNotification extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	// Search Variable
	public $batchName;
	public $eventTitle;
	public $notified_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['status', 'batch_id', 'notified_id', 'users'], 'integer'],
			[['batch_id', 'notified_date'], 'required'],
			[['notified_id', 'users', 'creation_date'], 'safe'],
			[['batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventBatch::className(), 'targetAttribute' => ['batch_id' => 'batch_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBatch()
	{
		return $this->hasOne(EventBatch::className(), ['batch_id' => 'batch_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNotified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'notified_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'status' => Yii::t('app', 'Enabled'),
			'batch_id' => Yii::t('app', 'Batch'),
			'notified_date' => Yii::t('app', 'Notified Date'),
			'notified_id' => Yii::t('app', 'Notified'),
			'users' => Yii::t('app', 'Users'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'batchName' => Yii::t('app', 'Batch'),
			'eventTitle' => Yii::t('app', 'Event'),
			'notified_search' => Yii::t('app', 'Notified'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('batch')) {
			$this->templateColumns['eventTitle'] = [
				'attribute' => 'eventTitle',
				'value' => function($model, $key, $index, $column) {
					return $model->batch->event->title;
				},
			];
			$this->templateColumns['batchName'] = [
				'attribute' => 'batchName',
				'value' => function($model, $key, $index, $column) {
					return $model->batch->batch_name;
				},
			];
		}
		$this->templateColumns['notified_date'] = [
			'attribute' => 'notified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'notified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->notified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->notified_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		// $this->templateColumns['notified_id'] = 'notified_id';
		$this->templateColumns['notified_search'] = [
			'attribute' => 'notified_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->notified->displayname) ? $model->notified->displayname : '-';
			},
		];
		$this->templateColumns['users'] = 'users';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return $model->status ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		if(parent::beforeSave($insert)) {
			// Create action
			if($this->isNewRecord) {
				if($this->notified_id == null)
					$this->notified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

				$notification = EventNotification::find()->where(['batch_id' => $this->batch_id, 'status' => 1])->one();
				$notification->status = 0;
				$notification->save(false);
			}

			$this->notified_date = Yii::$app->formatter->asDate($this->notified_date, 'php:Y-m-d');
		}
		return true;	
	}

	// /**
	//  * after validate attributes
	//  */
	// public function afterValidate()
	// {
	// 	parent::afterValidate();
	// 	// Create action
		
	// 	return true;
	// }
	
	// /**
	//  * After save attributes
	//  */
	// public function afterSave($insert, $changedAttributes) 
	// {
	// 	parent::afterSave($insert, $changedAttributes);
	// 	// Create action
	// }

	// /**
	//  * Before delete attributes
	//  */
	// public function beforeDelete() 
	// {
	// 	if(parent::beforeDelete()) {
	// 		// Create action
	// 	}
	// 	return true;
	// }

	// /**
	//  * After delete attributes
	//  */
	// public function afterDelete() 
	// {
	// 	parent::afterDelete();
	// 	// Create action
	// }
}
