<?php
/**
 * EventRegistered
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 29 November 2017, 15:42 WIB
 * @modified date 25 June 2019, 07:42 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_registered".
 *
 * The followings are the available columns in table "ommu_event_registered":
 * @property integer $id
 * @property integer $status
 * @property integer $event_id
 * @property integer $user_id
 * @property string $confirmation_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property EventRegisteredBatch[] $batches
 * @property EventRegisteredFinance $eventRegisteredFinance
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Html;
use app\models\Users;
use yii\base\Event;

class EventRegistered extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'price', 'payment', 'reward'];

	public $eventTitle;
	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $eventCategoryId;
	public $price;
	public $payment;
	public $reward;
	public $batch;

	const EVENT_BEFORE_SAVE_EVENT_REGISTERED = 'BeforeSaveEventRegistered';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_registered';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'user_id', 'batch'], 'required'],
			[['status', 'event_id', 'user_id', 'creation_id', 'modified_id'], 'integer'],
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
			'status' => Yii::t('app', 'Status'),
			'event_id' => Yii::t('app', 'Event'),
			'user_id' => Yii::t('app', 'User'),
			'confirmation_date' => Yii::t('app', 'Confirmation Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'eventTitle' => Yii::t('app', 'Event'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'eventCategoryId' => Yii::t('app', 'Category'),
			'batch' => Yii::t('app', 'Batches'),
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
	 * @param $type relation|array|dataProvider|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getBatches($type='relation', $val='id')
	{
		if($type == 'relation')
			return $this->hasMany(EventRegisteredBatch::className(), ['registered_id' => 'id']);

		if($type == 'array')
			return \yii\helpers\ArrayHelper::map($this->batches, 'batch_id', $val=='id' ? 'id' : 'batch.batch_name');

		if($type == 'dataProvider') {
			return new \yii\data\ActiveDataProvider([
				'query' => $this->getBatches('relation'),
			]);
		}

		$model = EventRegisteredBatch::find()
			->alias('t')
			->where(['t.registered_id' => $this->id]);
		$batches = $model->count();

		return $batches ? $batches : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFinance()
	{
		return $this->hasOne(EventRegisteredFinance::className(), ['registered_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
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
	 * @return \ommu\event\models\query\EventRegistered the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventRegistered(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['eventCategoryId'] = [
			'attribute' => 'eventCategoryId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->event->category) ? $model->event->category->title->message : '-';
				// return $model->eventCategoryId;
			},
			'filter' => EventCategory::getCategory(),
			'visible' => !Yii::$app->request->get('event') && !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['eventTitle'] = [
			'attribute' => 'eventTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->event) ? $model->event->title : '-';
				// return $model->eventTitle;
			},
			'visible' => !Yii::$app->request->get('event') && !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['batch'] = [
			'attribute' => 'batch',
			'value' => function($model, $key, $index, $column) {
				$batches = $model->getBatches('array', 'title');
				return Html::ul($batches, ['encode'=>false, 'class'=>'list-boxed']);
			},
			'filter' => self::getBatchFilter(),
			'format' => 'html',
		];
		$this->templateColumns['confirmation_date'] = [
			'attribute' => 'confirmation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->confirmation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'confirmation_date'),
		];
		$this->templateColumns['price'] = [
			'attribute' => 'price',
			'value' => function($model, $key, $index, $column) {
				return isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->price) : '-';
			},
		];
		$this->templateColumns['payment'] = [
			'attribute' => 'payment',
			'value' => function($model, $key, $index, $column) {
				return isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->payment) : '-';
			},
		];
		$this->templateColumns['reward'] = [
			'attribute' => 'reward',
			'value' => function($model, $key, $index, $column) {
				return isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->reward) : '-';
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return self::getStatus($model->status);
			},
			'filter' => self::getStatus(),
			'contentOptions' => ['class'=>'text-center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getStatus
	 */
	public static function getStatus($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Waiting'),
			'1' => Yii::t('app', 'Paid'),
			'2' => Yii::t('app', 'Cancel'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getBatchFilter
	 */
	public static function getBatchFilter()
	{
		if(($id = Yii::$app->request->get('id')) == null)
			return;

		$model = EventBatch::find()
			->published()
			->andWhere(['event_id' => $id])
			->all();

		if($model == null)
			return;

		return \yii\helpers\ArrayHelper::map($model, 'batch_name', 'batch_name');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->eventTitle = isset($this->event) ? $this->event->title : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->eventCategoryId = isset($this->event->category) ? $this->event->category->title->message : '-';
		$this->batch = array_flip($this->getBatches('array'));
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

			if($this->isNewRecord) {
				if($this->event->isPackage)
					$this->batch = array_values(array_flip($this->event->getBatches('array')));

				if($this->user_id && !isset($this->user))
					$this->addError('user_id', Yii::t('app', 'User not registered yet'));

				// cek user registereds
				$registered = self::find()
					->andWhere(['<>', 'status', 1])
					->andWhere(['event_id' => $this->event_id])
					->andWhere(['user_id' => $this->user_id])
					->one();

				if($registered != null)
					$this->addError('user_id', Yii::t('app', 'User <strong>{displayname}</strong> sudah terdaftar', ['displayname'=>$this->user->displayname]));
			} else {
				if($this->status == '')
					$this->addError('status', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('status')]));
			}
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert) {
			// set batches
			$this->isNewRecord = true;
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_EVENT_REGISTERED, $event);
		}
	}
}
