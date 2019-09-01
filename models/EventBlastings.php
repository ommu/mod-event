<?php
/**
 * EventBlastings

 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 13:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_blastings".
 *
 * The followings are the available columns in table "ommu_event_blastings":
 * @property string $blast_id
 * @property string $event_id
 * @property string $filter_id
 * @property integer $users
 * @property string $blast_with
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property EventBlastingItem[] $items
 * @property Events $event
 * @property BlastingFilter $filter
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;
use app\modules\blasting\models\BlastingFilter;

class EventBlastings extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creationDisplayname', 'creation_date', 'modified_date', 'modifiedDisplayname'];

	// Search Variable
	public $eventTitle;
	public $filter_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $filter_i;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_blastings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			// [['event_id', 'users', 'blast_with', 'creation_id', 'modified_id'], 'required'],
			[['event_id', 'filter_i'], 'required'],
			[['event_id', 'filter_id', 'users', 'creation_id', 'modified_id'], 'integer'],
			[['blast_with'], 'string'],
			// [['creation_date', 'modified_date'], 'safe'],
			[['creation_date', 'users', 'blast_with', 'creation_id', 'modified_id', 'modified_date'], 'safe'],
			[['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
			[['filter_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlastingFilter::className(), 'targetAttribute' => ['filter_id' => 'filter_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'blast_id' => Yii::t('app', 'Blast'),
			'event_id' => Yii::t('app', 'Event'),
			'filter_id' => Yii::t('app', 'Filter'),
			'users' => Yii::t('app', 'Users'),
			'blast_with' => Yii::t('app', 'Blast With'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'eventTitle' => Yii::t('app', 'Event'),
			'filter_search' => Yii::t('app', 'Filter'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'filter_i[gender]' => Yii::t('app', 'Gender'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getItems()
	{
		return $this->hasMany(EventBlastingItem::className(), ['blast_id' => 'blast_id']);
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
	public function getFilter()
	{
		return $this->hasOne(BlastingFilter::className(), ['filter_id' => 'filter_id']);
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
					return $model->event->title;
				},
			];
		}
		if(!Yii::$app->request->get('filter')) {
			$this->templateColumns['filter_search'] = [
				'attribute' => 'filter_search',
				'value' => function($model, $key, $index, $column) {
					$blasting_filter = new BlastingFilter();
					$blasting_filter_array = $blasting_filter->getFilter($model->filter_id)['gender'];
					$value = 'gender : '.implode(', ', $blasting_filter_array);

					return isset($model->filter_id) ? $value : '-';
				},
			];
		}
		// $this->templateColumns['users'] = 'users';
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['blasting-item/index', 'blasting'=>$model->primaryKey]);
				return Html::a($model->users ? $model->users : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['blast_with'] = 'blast_with';
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
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
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
		if(parent::beforeSave($insert)) {
			if(!Yii::$app->request->get('blast_id')) {
				$value = serialize($this->filter_i);

				$blasting_filter = BlastingFilter::find()->where(['filter_value' => $value])->one();
				if ($blasting_filter == null) {
					$blasting_filter = new BlastingFilter();
					$blasting_filter->filter_value = $value;
					$blasting_filter->save(false);
				}

				$this->filter_id = $blasting_filter->filter_id;
			}
		}
		return true;	
	}


}
