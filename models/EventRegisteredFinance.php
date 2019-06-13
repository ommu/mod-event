<?php
/**
 * EventRegisteredFinance

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:45 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_registered_finance".
 *
 * The followings are the available columns in table "ommu_event_registered_finance":
 * @property string $registered_id
 * @property integer $payment
 * @property integer $reward
 * @property string $creation_date
 * @property string $creation_id
 *
 * The followings are the available model relations:
 * @property EventRegistered $registered
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class EventRegisteredFinance extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname'];

	// Search Variable
	public $registered_search;
	public $creationDisplayname;
	public $eventTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_registered_finance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['payment', 'reward', 'creation_id'], 'integer'],
			[['payment', 'reward'], 'required'],
			[['creation_date', 'creation_id'], 'safe'],
			[['registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventRegistered::className(), 'targetAttribute' => ['registered_id' => 'registered_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistered()
	{
		return $this->hasOne(EventRegistered::className(), ['id' => 'registered_id']);
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
			'registered_id' => Yii::t('app', 'Registered'),
			'payment' => Yii::t('app', 'payment'),
			'reward' => Yii::t('app', 'reward'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'registered_search' => Yii::t('app', 'Registered'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'eventTitle' => Yii::t('app', 'Event'),
			'userDisplayname' => Yii::t('app', 'User'),
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
		$this->templateColumns['eventTitle'] = [
			'attribute' => 'eventTitle',
			'value' => function($model, $key, $index, $column) {
				return $model->registered->event->title;
			},
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->registered->user->displayname) ? $model->registered->user->displayname : '-';
			},
		];
		$this->templateColumns['payment'] = 'payment';
		$this->templateColumns['reward'] = 'reward';
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
					return isset($model->creation) ? $model->creation->displayname : '-';
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
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
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
