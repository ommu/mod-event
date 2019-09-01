<?php
/**
 * EventTag
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 27 November 2017, 09:29 WIB
 * @modified date 23 June 2019, 15:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_tag".
 *
 * The followings are the available columns in table "ommu_event_tag":
 * @property integer $id
 * @property integer $event_id
 * @property integer $tag_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property CoreTags $tag
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use app\models\CoreTags;
use ommu\users\models\Users;

class EventTag extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $tagBody;
	public $eventTitle;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'tag_id'], 'required'],
			[['event_id', 'tag_id', 'creation_id'], 'integer'],
			[['tagBody'], 'string'],
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
			'tag_id' => Yii::t('app', 'Tag'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'tagBody' => Yii::t('app', 'Tag'),
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
	public function getTag()
	{
		return $this->hasOne(CoreTags::className(), ['tag_id' => 'tag_id']);
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
	 * @return \ommu\event\models\query\EventTag the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventTag(get_called_class());
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
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('event')) {
			$this->templateColumns['eventTitle'] = [
				'attribute' => 'eventTitle',
				'value' => function($model, $key, $index, $column) {
					return isset($model->event) ? $model->event->title : '-';
					// return $model->eventTitle;
				},
			];
		}
		if(!Yii::$app->request->get('tag')) {
			$this->templateColumns['tagBody'] = [
				'attribute' => 'tagBody',
				'value' => function($model, $key, $index, $column) {
					return isset($model->tag) ? $model->tag->body : '-';
					// return $model->tagBody;
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

		$this->tagBody = isset($this->tag) ? $this->tag->body : '';
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
