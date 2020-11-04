<?php
/**
 * EventSpeaker
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 11:42 WIB
 * @modified date 26 June 2019, 21:46 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_speaker".
 *
 * The followings are the available columns in table "ommu_event_speaker":
 * @property integer $id
 * @property integer $publish
 * @property integer $batch_id
 * @property integer $user_id
 * @property string $speaker_name
 * @property string $speaker_position
 * @property string $session_title
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property EventBatch $batch
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use app\models\Users;

class EventSpeaker extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['userDisplayname', 'creationDisplayname', 'creation_date', 'modifiedDisplayname', 'modified_date', 'updated_date'];

	public $batchName;
	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $eventCategoryId;
	public $eventTitle;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_speaker';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['batch_id', 'speaker_name', 'speaker_position'], 'required'],
			[['publish', 'batch_id', 'user_id', 'creation_id', 'modified_id'], 'integer'],
			[['user_id', 'session_title'], 'safe'],
			[['speaker_name', 'speaker_position'], 'string', 'max' => 64],
			[['session_title'], 'string', 'max' => 128],
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
			'publish' => Yii::t('app', 'Publish'),
			'batch_id' => Yii::t('app', 'Batch'),
			'user_id' => Yii::t('app', 'User'),
			'speaker_name' => Yii::t('app', 'Speaker Name'),
			'speaker_position' => Yii::t('app', 'Speaker Position'),
			'session_title' => Yii::t('app', 'Session Title'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'batchName' => Yii::t('app', 'Batch'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'eventCategoryId' => Yii::t('app', 'Category'),
			'eventTitle' => Yii::t('app', 'Event'),
		];
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
	 * @return \ommu\event\models\query\EventSpeaker the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventSpeaker(get_called_class());
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
		$this->templateColumns['eventCategoryId'] = [
			'attribute' => 'eventCategoryId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->batch->event->category) ? $model->batch->event->category->title->message : '-';
				// return $model->eventCategoryId;
			},
			'filter' => EventCategory::getCategory(),
			'visible' => !Yii::$app->request->get('batch') && !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['eventTitle'] = [
			'attribute' => 'eventTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->batch->event) ? $model->batch->event->title : '-';
				// return $model->eventTitle;
			},
			'visible' => !Yii::$app->request->get('batch') && !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['batchName'] = [
			'attribute' => 'batchName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->batch) ? $model->batch->batch_name : '-';
				// return $model->batchName;
			},
			'visible' => !Yii::$app->request->get('batch') && !Yii::$app->request->get('id') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['speaker_name'] = [
			'attribute' => 'speaker_name',
			'value' => function($model, $key, $index, $column) {
				return $model->speaker_name;
			},
		];
		$this->templateColumns['speaker_position'] = [
			'attribute' => 'speaker_position',
			'value' => function($model, $key, $index, $column) {
				return $model->speaker_position;
			},
		];
		$this->templateColumns['session_title'] = [
			'attribute' => 'session_title',
			'value' => function($model, $key, $index, $column) {
				return $model->session_title;
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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

		// $this->batchName = isset($this->batch) ? $this->batch->batch_name : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->eventCategoryId = isset($this->batch->event->category) ? $this->batch->event->category->title->message : '-';
		// $this->eventTitle = isset($this->batch->event) ? $this->batch->event->title : '-';
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
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
