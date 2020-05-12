<?php
/**
 * EventFilterMajorGroup
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 24 June 2019, 13:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_filter_major_group".
 *
 * The followings are the available columns in table "ommu_event_filter_major_group":
 * @property integer $id
 * @property integer $event_id
 * @property integer $major_group_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property IpediaMajorGroup $majorGroup
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use app\models\Users;
use ommu\ipedia\models\IpediaMajorGroup;

class EventFilterMajorGroup extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $eventTitle;
	public $majorGroupGroupName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_filter_major_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'major_group_id'], 'required'],
			[['event_id', 'major_group_id', 'creation_id'], 'integer'],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
			[['major_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaMajorGroup::className(), 'targetAttribute' => ['major_group_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'event_id' => Yii::t('app', 'Event'),
			'major_group_id' => Yii::t('app', 'Major Group'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'eventTitle' => Yii::t('app', 'Event'),
			'majorGroupGroupName' => Yii::t('app', 'Majorgroup'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
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
	public function getGroup()
	{
		return $this->hasOne(IpediaMajorGroup::className(), ['id' => 'major_group_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\query\EventFilterMajorGroup the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventFilterMajorGroup(get_called_class());
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
		$this->templateColumns['eventTitle'] = [
			'attribute' => 'eventTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->event) ? $model->event->title : '-';
				// return $model->eventTitle;
			},
			'visible' => !Yii::$app->request->get('event') ? true : false,
		];
		$this->templateColumns['major_group_id'] = [
			'attribute' => 'major_group_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->majorGroup) ? $model->majorGroup->group_name : '-';
				// return $model->majorGroupGroupName;
			},
			'filter' => IpediaMajorGroup::getGroup(),
			'visible' => !Yii::$app->request->get('majorGroup') ? true : false,
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->eventTitle = isset($this->event) ? $this->event->title : '-';
		// $this->majorGroupGroupName = isset($this->majorGroup) ? $this->majorGroup->group_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
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
			}
		}
		return true;
	}
}
