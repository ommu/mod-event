<?php
/**
 * EventSetting

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 09:36 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_setting".
 *
 * The followings are the available columns in table "ommu_event_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $event_price
 * @property string $event_agreement
 * @property string $event_warning_message
 * @property string $event_notify_diff_type
 * @property integer $event_notify_difference
 * @property string $event_banned_diff_type
 * @property integer $event_banned_difference
 * @property string $modified_date
 * @property string $modified_id
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class EventSetting extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Search Variable
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_keyword', 'meta_description', 'event_price', 'event_agreement', 'event_warning_message', 'event_notify_diff_type', 'event_notify_difference', 'event_banned_diff_type', 'event_banned_difference'], 'required'],
			[['permission', 'event_price', 'event_notify_difference', 'event_banned_difference', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description', 'event_agreement', 'event_warning_message', 'event_notify_diff_type', 'event_banned_diff_type'], 'string'],
			[['modified_date', 'modified_id'], 'safe'],
			[['license'], 'string', 'max' => 32],
		];
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
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'event_price' => Yii::t('app', 'Event Price'),
			'event_agreement' => Yii::t('app', 'Event Agreement'),
			'event_warning_message' => Yii::t('app', 'Event Warning Message'),
			'event_notify_diff_type' => Yii::t('app', 'Event Notify Diff Type'),
			'event_notify_difference' => Yii::t('app', 'Event Notify Difference'),
			'event_banned_diff_type' => Yii::t('app', 'Event Banned Diff Type'),
			'event_banned_difference' => Yii::t('app', 'Event Banned Difference'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
		$this->templateColumns['license'] = 'license';
		$this->templateColumns['meta_keyword'] = 'meta_keyword';
		$this->templateColumns['meta_description'] = 'meta_description';
		$this->templateColumns['event_price'] = 'event_price';
		$this->templateColumns['event_agreement'] = 'event_agreement';
		$this->templateColumns['event_warning_message'] = 'event_warning_message';
		$this->templateColumns['event_notify_diff_type'] = 'event_notify_diff_type';
		$this->templateColumns['event_notify_difference'] = 'event_notify_difference';
		$this->templateColumns['event_banned_diff_type'] = 'event_banned_diff_type';
		$this->templateColumns['event_banned_difference'] = 'event_banned_difference';
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->modified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified->displayname) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return $model->permission;
			},
			'contentOptions' => ['class'=>'center'],
		];
	}

	
	/**
	 * getLicense
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord)
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
		}
		return true;
	}

	// /**
	//  * before save attributes
	//  */
	// public function beforeSave($insert) 
	// {
	// 	if(parent::beforeSave($insert)) {
	// 		// Create action
	// 	}
	// 	return true;	
	// }

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
