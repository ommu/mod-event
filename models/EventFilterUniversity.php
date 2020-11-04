<?php
/**
 * EventFilterUniversity
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 09:23 WIB
 * @modified date 24 June 2019, 13:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_filter_university".
 *
 * The followings are the available columns in table "ommu_event_filter_university":
 * @property integer $id
 * @property integer $event_id
 * @property integer $university_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Events $event
 * @property IpediaUniversities $university
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use app\models\Users;
use ommu\ipedia\models\IpediaUniversities;

class EventFilterUniversity extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $eventTitle;
	public $universityName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_filter_university';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'university_id'], 'required'],
			[['event_id', 'university_id', 'creation_id'], 'integer'],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
			[['university_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaUniversities::className(), 'targetAttribute' => ['university_id' => 'university_id']],
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
			'university_id' => Yii::t('app', 'University'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'eventTitle' => Yii::t('app', 'Event'),
			'universityName' => Yii::t('app', 'University'),
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
	public function getUniversity()
	{
		return $this->hasOne(IpediaUniversities::className(), ['university_id' => 'university_id']);
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
	 * @return \ommu\event\models\query\EventFilterUniversity the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventFilterUniversity(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

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
		$this->templateColumns['universityName'] = [
			'attribute' => 'universityName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->university) ? $model->university->company->company_name : '-';
				// return $model->universityName;
			},
			'visible' => !Yii::$app->request->get('university') ? true : false,
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
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
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
		// $this->universityName = isset($this->university) ? $this->university->company->company_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
