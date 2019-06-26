<?php
/**
 * EventRegisteredBatch
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 26 June 2019, 21:42 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_registered_batch".
 *
 * The followings are the available columns in table "ommu_event_registered_batch":
 * @property integer $id
 * @property integer $registered_id
 * @property integer $batch_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property EventRegistered $registered
 * @property EventBatch $batch
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use ommu\users\models\Users;

class EventRegisteredBatch extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $registeredEventId;
	public $batchName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_registered_batch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['registered_id', 'batch_id'], 'required'],
			[['registered_id', 'batch_id', 'creation_id'], 'integer'],
			[['registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventRegistered::className(), 'targetAttribute' => ['registered_id' => 'id']],
			[['batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventBatch::className(), 'targetAttribute' => ['batch_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'registered_id' => Yii::t('app', 'Registered'),
			'batch_id' => Yii::t('app', 'Batch'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'registeredEventId' => Yii::t('app', 'Registered'),
			'batchName' => Yii::t('app', 'Batch'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
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
	public function getBatch()
	{
		return $this->hasOne(EventBatch::className(), ['id' => 'batch_id']);
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
	 * @return \ommu\event\models\query\EventRegisteredBatch the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventRegisteredBatch(get_called_class());
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
		if(!Yii::$app->request->get('registered')) {
			$this->templateColumns['registeredEventId'] = [
				'attribute' => 'registeredEventId',
				'value' => function($model, $key, $index, $column) {
					return isset($model->registered) ? $model->registered->event->title : '-';
					// return $model->registeredEventId;
				},
			];
		}
		if(!Yii::$app->request->get('batch')) {
			$this->templateColumns['batchName'] = [
				'attribute' => 'batchName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->batch) ? $model->batch->batch_name : '-';
					// return $model->batchName;
				},
			];
		}
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->registeredEventId = isset($this->registered) ? $this->registered->event->title : '-';
		// $this->batchName = isset($this->batch) ? $this->batch->batch_name : '-';
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