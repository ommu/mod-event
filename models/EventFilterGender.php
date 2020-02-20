<?php
/**
 * EventFilterGender
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:13 WIB
 * @modified date 24 June 2019, 13:19 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_filter_gender".
 *
 * The followings are the available columns in table "ommu_event_filter_gender":
 * @property integer $id
 * @property integer $event_id
 * @property string $gender
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use ommu\users\models\Users;

class EventFilterGender extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $eventTitle;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_filter_gender';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'gender'], 'required'],
			[['event_id', 'creation_id'], 'integer'],
			[['gender'], 'string'],
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
			'event_id' => Yii::t('app', 'Event'),
			'gender' => Yii::t('app', 'Gender'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'eventTitle' => Yii::t('app', 'Event'),
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
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\query\EventFilterGender the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventFilterGender(get_called_class());
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
		$this->templateColumns['gender'] = [
			'attribute' => 'gender',
			'value' => function($model, $key, $index, $column) {
				return self::getGender($model->gender);
			},
			'filter' => self::getGender(),
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
	 * function getGender
	 */
	public static function getGender($value=null)
	{
		$items = array(
			'male' => Yii::t('app', 'Male'),
			'female' => Yii::t('app', 'Female'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->eventTitle = isset($this->event) ? $this->event->title : '-';
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
