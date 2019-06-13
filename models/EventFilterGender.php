<?php
/**
 * EventFilterGender

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:13 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_filter_gender".
 *
 * The followings are the available columns in table "ommu_event_filter_gender":
 * @property string $filter_id
 * @property string $event_id
 * @property string $gender
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Events $event
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class EventFilterGender extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Search Variable
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
			[['event_id', 'gender', 'creation_id'], 'required'],
			[['event_id', 'creation_id'], 'integer'],
			[['gender'], 'string'],
			[['creation_date'], 'safe'],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'event_id']],
		];
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
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'filter_id' => Yii::t('app', 'Filter'),
			'event_id' => Yii::t('app', 'Event'),
			'gender' => Yii::t('app', 'Gender'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'eventTitle' => Yii::t('app', 'Event'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
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
			'class'  => 'yii\grid\SerialColumn',
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
		$this->templateColumns['gender'] = 'gender';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->creation_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation->displayname) ? $model->creation->displayname : '-';
				},
			];
		}
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
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
