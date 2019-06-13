<?php
/**
 * EventUserBanned

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 10:18 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_user_banned".
 *
 * The followings are the available columns in table "ommu_event_user_banned":
 * @property string $banned_id
 * @property integer $status
 * @property string $event_id
 * @property string $user_id
 * @property string $banned_start
 * @property string $banned_end
 * @property string $banned_desc
 * @property string $unbanned_agreement
 * @property string $unbanned_date
 * @property string $unbanned_id
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class EventUserBanned extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creationDisplayname', 'modifiedDisplayname', 'modified_date'];

	// Search Variable
	public $user_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $unbanned_search;
	public $eventTitle;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_user_banned';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['status', 'event_id', 'user_id', 'unbanned_id', 'creation_id', 'modified_id'], 'integer'],
			// [['event_id', 'user_id', 'banned_desc', 'unbanned_agreement', 'unbanned_id', 'creation_id', 'modified_id'], 'required'],
			[['event_id', 'user_id', 'banned_desc'], 'required', 'on' => 'createForm'],
			[['banned_desc', 'unbanned_agreement'], 'required', 'on' => 'unbannedForm'],
			[['banned_start', 'banned_end', 'unbanned_date', 'modified_date', 'unbanned_id', 'creation_id', 'modified_id'], 'safe'],
			[['banned_desc', 'unbanned_agreement'], 'string'],
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getUnbanned()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'unbanned_id']);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'banned_id' => Yii::t('app', 'Banned ID'),
			'status' => Yii::t('app', 'Status'),
			'event_id' => Yii::t('app', 'Event'),
			'user_id' => Yii::t('app', 'User'),
			'banned_start' => Yii::t('app', 'Banned Start'),
			'banned_end' => Yii::t('app', 'Banned End'),
			'banned_desc' => Yii::t('app', 'Banned Desc'),
			'unbanned_agreement' => Yii::t('app', 'Unbanned Agreement'),
			'unbanned_date' => Yii::t('app', 'Unbanned Date'),
			'unbanned_id' => Yii::t('app', 'Unbanned'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'user_search' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'unbanned_search' => Yii::t('app', 'Unbanned'),
			'eventTitle' => Yii::t('app', 'Event'),
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
		// $this->templateColumns['event_id'] = 'event_id';
		if(!Yii::$app->request->get('event')) {
			$this->templateColumns['eventTitle'] = [
				'attribute' => 'eventTitle',
				'value' => function($model, $key, $index, $column) {
					return $model->event->title;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->displayname) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['banned_start'] = [
			'attribute' => 'banned_start',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'banned_start',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->banned_start, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->banned_start, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		$this->templateColumns['banned_end'] = [
			'attribute' => 'banned_end',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'banned_end',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->banned_end, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->banned_end, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		$this->templateColumns['banned_desc'] = 'banned_desc';
		$this->templateColumns['unbanned_agreement'] = 'unbanned_agreement';
		$this->templateColumns['unbanned_date'] = [
			'attribute' => 'unbanned_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'unbanned_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->unbanned_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->unbanned_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		// $this->templateColumns['unbanned_id'] = 'unbanned_id';
		if(!Yii::$app->request->get('unbanned')) {
			$this->templateColumns['unbanned_search'] = [
				'attribute' => 'unbanned_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->unbanned->displayname) ? $model->unbanned->displayname : '-';
				},
			];
		}	
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation->displayname) ? $model->creation->displayname : '-';
				},
			];
		}
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
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'filter'=> array(1=>'Banned',0=>'Unbanned'),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['unbanned', 'id'=>$model->primaryKey]);
				return $model->status ? Html::a(Yii::t('app', 'Banned'), $url) :  Yii::t('app', 'Unbanned');
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
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
		$action = strtolower(Yii::$app->controller->action->id);

		if(parent::beforeSave($insert)) {
			// Create action
			if ($action == 'unbanned') {
				$this->status = 0;
				if($this->unbanned_id == null)
					$this->unbanned_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
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
