<?php
/**
 * EventBatch
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:38 WIB
 * @modified date 23 June 2019, 16:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_batch".
 *
 * The followings are the available columns in table "ommu_event_batch":
 * @property integer $id
 * @property integer $publish
 * @property integer $event_id
 * @property string $batch_name
 * @property string $batch_desc
 * @property string $batch_date
 * @property string $batch_time
 * @property integer $batch_price
 * @property string $batch_location
 * @property string $location_name
 * @property string $location_address
 * @property integer $registered_limit
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property EventNotification[] $notifications
 * @property EventRegisteredBatch[] $registereds
 * @property EventSpeaker[] $speakers
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use yii\data\ActiveDataProvider;

class EventBatch extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['batch_desc', 'batch_time', 'batch_price', 'batch_location', 'location_name', 'location_address', 'registered_limit', 'creationDisplayname', 'creation_date', 'modifiedDisplayname', 'modified_date', 'updated_date'];

	public $eventTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $eventCategoryId;
	public $speaker;

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
			[['event_id', 'batch_name', 'batch_desc', 'batch_date', 'batch_price', 'registered_limit'], 'required'],
			[['publish', 'event_id', 'batch_price', 'registered_limit', 'creation_id', 'modified_id'], 'integer'],
			[['batch_desc'], 'string'],
			//[['batch_time'], 'serialize'],
			[['batch_date', 'batch_time'], 'safe'],
			[['batch_name', 'location_name'], 'string', 'max' => 128],
			[['batch_location'], 'string', 'max' => 256],
			[['location_address'], 'string', 'max' => 512],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'event_id' => Yii::t('app', 'Event'),
			'batch_name' => Yii::t('app', 'Batch'),
			'batch_desc' => Yii::t('app', 'Description'),
			'batch_date' => Yii::t('app', 'Batch Date'),
			'batch_time' => Yii::t('app', 'Batch Time'),
			'batch_time[start]' => Yii::t('app', 'Time Start'),
			'batch_time[end]' => Yii::t('app', 'Time Finish'),
			'batch_price' => Yii::t('app', 'Registered Price'),
			'batch_location' => Yii::t('app', 'Batch Location'),
			'location_name' => Yii::t('app', 'Location Name'),
			'location_address' => Yii::t('app', 'Location Address'),
			'registered_limit' => Yii::t('app', 'Registered Limit'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'registereds' => Yii::t('app', 'Registereds'),
			'speaker' => Yii::t('app', 'Speakers'),
			'eventTitle' => Yii::t('app', 'Event'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'eventCategoryId' => Yii::t('app', 'Category'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvent()
	{
		return $this->hasOne(Events::className(), ['id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNotifications()
	{
		return $this->hasMany(EventNotification::className(), ['batch_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistereds($count=false)
	{
		if($count == false)
			return $this->hasMany(EventRegisteredBatch::className(), ['batch_id' => 'id']);

		$model = EventRegisteredBatch::find()
			->where(['batch_id' => $this->id]);
		$batches = $model->count();

		return $batches ? $batches : 0;
	}

	/**
	 * @param $type relation|array|dataProvider|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpeakers($type='relation', $publish=1)
	{
		if($type == 'relation')
			return $this->hasMany(EventSpeaker::className(), ['batch_id' => 'id'])
			->alias('speakers')
			->andOnCondition([sprintf('%s.publish', 'speakers') => $publish]);

		if($type == 'array')
			return \yii\helpers\ArrayHelper::map($this->speakers, 'speaker_name', 'speaker_name');

		if($type == 'dataProvider') {
			return new ActiveDataProvider([
				'query' => $this->getSpeakers($type='relation', $publish),
			]);
		}

		$model = EventSpeaker::find()
			->where(['batch_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$speakers = $model->count();

		return $speakers ? $speakers : 0;
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
	 * {@inheritdoc}
	 * @return \ommu\event\models\query\EventBatch the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventBatch(get_called_class());
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
		if(!Yii::$app->request->get('event') && !Yii::$app->request->get('id')) {
			$this->templateColumns['eventCategoryId'] = [
				'attribute' => 'eventCategoryId',
				'value' => function($model, $key, $index, $column) {
					return isset($model->event->category) ? $model->event->category->title->message : '-';
					// return $model->eventCategoryId;
				},
				'filter' => EventCategory::getCategory(),
			];
			$this->templateColumns['eventTitle'] = [
				'attribute' => 'eventTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->event) ? $model->event->title : '-';
					// return $model->eventTitle;
				},
			];
		}
		$this->templateColumns['batch_name'] = [
			'attribute' => 'batch_name',
			'value' => function($model, $key, $index, $column) {
				return $model->batch_name;
			},
		];
		$this->templateColumns['batch_desc'] = [
			'attribute' => 'batch_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->batch_desc;
			},
			'format' => 'html',
		];
		$this->templateColumns['batch_date'] = [
			'attribute' => 'batch_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->batch_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'batch_date'),
		];
		$this->templateColumns['batch_time'] = [
			'attribute' => 'batch_time',
			'value' => function($model, $key, $index, $column) {
				return self::parseBatchTime($model->batch_time);
			},
		];
		$this->templateColumns['speaker'] = [
			'attribute' => 'speaker',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getSpeakers('array'));
			},
		];
		$this->templateColumns['batch_price'] = [
			'attribute' => 'batch_price',
			'value' => function($model, $key, $index, $column) {
				return $model->batch_price;
			},
		];
		$this->templateColumns['batch_location'] = [
			'attribute' => 'batch_location',
			'value' => function($model, $key, $index, $column) {
				return $model->batch_location;
			},
		];
		$this->templateColumns['location_name'] = [
			'attribute' => 'location_name',
			'value' => function($model, $key, $index, $column) {
				return $model->location_name;
			},
		];
		$this->templateColumns['location_address'] = [
			'attribute' => 'location_address',
			'value' => function($model, $key, $index, $column) {
				return $model->location_address;
			},
		];
		$this->templateColumns['registered_limit'] = [
			'attribute' => 'registered_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->registered_limit;
			},
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
					// return $model->creationDisplayname;
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['registereds'] = [
			'attribute' => 'registereds',
			'value' => function($model, $key, $index, $column) {
				$registereds = $model->getRegistereds(true);
				return Html::a($registereds, ['registered/batch/manage', 'batch'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} registereds', ['count'=>$registereds])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
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
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * User get information
	 */
	public static function parseBatchTime($batchTime)
	{
		if(!is_array($batchTime) || (is_array($batchTime) && empty($batchTime)))
			return '-';

		if($batchTime['start'] == '' && $batchTime['end'] == '')
			return '-';

		return $batchTime['start'].' - '.($batchTime['end'] ? $batchTime['end'] : Yii::t('app', 'Finish'));
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->batch_time = unserialize($this->batch_time);
		// $this->eventTitle = isset($this->event) ? $this->event->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->eventCategoryId = isset($this->event->category) ? $this->event->category->title->message : '-';
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

			if($this->batch_time['start'] == '')
				$this->addError('batch_time', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('batch_time')]));
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
			$this->batch_time = serialize($this->batch_time);
		}
		return true;
	}
}
