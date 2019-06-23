<?php
/**
 * EventBatch
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:38 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_batch".
 *
 * The followings are the available columns in table "ommu_event_batch":
 * @property string $batch_id
 * @property integer $publish
 * @property string $event_id
 * @property string $batch_name
 * @property string $batch_date
 * @property string $batch_time
 * @property integer $registered_limit
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property EventSpeaker[] $advisers
 * @property Events $event
 * @property EventNotification[] $notifications
 * @property EventRegistered[] $registereds
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;
use ommu\event\models\view\EventBatch as EventBatchView;

class EventBatch extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creationDisplayname', 'creation_date', 'modifiedDisplayname', 'modified_date', 'updated_date'];

	// Search Variable
	public $eventTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $adviser_search;
	public $adviser_all_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_batch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'event_id', 'registered_limit', 'creation_id', 'modified_id'], 'integer'],
			[['event_id', 'batch_name', 'batch_date', 'batch_time', 'registered_limit', 'creation_id'], 'required'],
			[['batch_date', 'creation_date', 'modified_id', 'modified_date', 'updated_date'], 'safe'],
			[['batch_time'], 'string'],
			[['batch_name'], 'string', 'max' => 128],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'event_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdvisers()
	{
		return $this->hasMany(EventSpeaker::className(), ['batch_id' => 'batch_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvent()
	{
		return $this->hasOne(Events::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNotifications()
	{
		return $this->hasMany(EventNotification::className(), ['batch_id' => 'batch_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistereds()
	{
		return $this->hasMany(EventRegistered::className(), ['batch_id' => 'batch_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(EventBatchView::className(), ['batch_id' => 'batch_id']);
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'batch_id' => Yii::t('app', 'Batch'),
			'publish' => Yii::t('app', 'Publish'),
			'event_id' => Yii::t('app', 'Event'),
			'batch_name' => Yii::t('app', 'Batch Title'),
			'batch_date' => Yii::t('app', 'Batch Date'),
			'batch_time' => Yii::t('app', 'Batch Time'),
			'registered_limit' => Yii::t('app', 'Registered Limit'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'eventTitle' => Yii::t('app', 'Event'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'adviser_search' => Yii::t('app', 'Adviser'),
			'adviser_all_search' => Yii::t('app', 'Adviser All'),
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
		if(!Yii::$app->request->get('event')) {
			$this->templateColumns['eventTitle'] = [
				'attribute' => 'eventTitle',
				'value' => function($model, $key, $index, $column) {
					return $model->event->title;
				},
			];
		}
		$this->templateColumns['batch_name'] = 'batch_name';
		$this->templateColumns['batch_date'] = [
			'attribute' => 'batch_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->batch_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'batch_date'),
		];
		$this->templateColumns['batch_time'] = 'batch_time';
		$this->templateColumns['registered_limit'] = 'registered_limit';
		$this->templateColumns['adviser_search'] = [
			'attribute' => 'adviser_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['adviser/index', 'batch'=>$model->primaryKey, 'publish' => true]);
				return Html::a($model->view->advisers ? $model->view->advisers : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['adviser_all_search'] = [
			'attribute' => 'adviser_all_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['adviser/index', 'batch'=>$model->primaryKey]);
				return Html::a($model->view->adviser_all ? $model->view->adviser_all : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->updated_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * function getBatch
	 */
	public static function getBatch($publish=null) 
	{
		$items = [];
		$model = self::find()->alias('b')->joinWith('event event');
		if ($publish!=null)
			$model = $model->andWhere(['b.publish'=>$publish]);
		$model = $model->orderBy('event.title ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->batch_id] = $val->event->title.' - '.$val->batch_name;
			}
		}
		
		return $items;
	}

	/**
	 * function getBatchRegister
	 */
	public static function getBatchRegister($publish, $event) 
	{
		$items = [];
		$model = self::find()->alias('b')->joinWith('event event');
		$model = $model->andWhere(['b.publish'=>$publish])->andWhere(['event.event_id' => $event]);
		$model = $model->orderBy('b.batch_time ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->batch_id] = $val->batch_time.' - '.$val->batch_name;
			}
		}
		
		return $items;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		if(parent::beforeSave($insert)) {
			$this->batch_date = Yii::$app->formatter->asDate($this->batch_date, 'php:Y-m-d');
		}
		return true;	
	}

	/**
	 * after validate attributes
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		// Create action
		
		return true;
	}
	
	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		parent::afterSave($insert, $changedAttributes);
		// Create action
	}

	/**
	 * Before delete attributes
	 */
	public function beforeDelete() 
	{
		if(parent::beforeDelete()) {
			// Create action
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete() 
	{
		parent::afterDelete();
		// Create action
	}
}
