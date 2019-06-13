<?php
/**
 * Events

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 27 November 2017, 09:41 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "_view_events".
 *
 * The followings are the available columns in table "_view_events":
 * @property string $event_id
 * @property string $started_date
 * @property string $ended_date
 * @property string $tags
 * @property string $tag_all
 * @property string $batchs
 * @property string $batch_all
 * @property integer $blasting_condition
 * @property string $blastings
 * @property string $registered
 * @property string $confirm
 * @property string $no_confirm
 *
 */

namespace ommu\event\models\view;

use Yii;
use yii\helpers\Url;

class Events extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_events';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['event_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['event_id', 'tag_all', 'batch_all', 'blasting_condition', 'blastings'], 'integer'],
			[['started_date', 'ended_date'], 'safe'],
			[['tags', 'batchs', 'registered', 'confirm', 'no_confirm'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'event_id' => Yii::t('app', 'Event'),
			'started_date' => Yii::t('app', 'Started Date'),
			'ended_date' => Yii::t('app', 'Ended Date'),
			'tags' => Yii::t('app', 'Tags'),
			'tag_all' => Yii::t('app', 'Tag All'),
			'batchs' => Yii::t('app', 'Batchs'),
			'batch_all' => Yii::t('app', 'Batch All'),
			'blasting_condition' => Yii::t('app', 'Blasting Condition'),
			'blastings' => Yii::t('app', 'Blastings'),
			'registered' => Yii::t('app', 'Registered'),
			'confirm'=>Yii::t('app', 'Confirm'),
			'no_confirm' => Yii::t('app', 'No Confirm'),
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
		$this->templateColumns['event_id'] = 'event_id';
		$this->templateColumns['started_date'] = [
			'attribute' => 'started_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'started_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->started_date, 
					['0000-00-00','1970-01-01','0002-12-02','-0001-11-30'])) {
					return Yii::$app->formatter->format($model->started_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		$this->templateColumns['ended_date'] = [
			'attribute' => 'ended_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'ended_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->ended_date, 
					['0000-00-00','1970-01-01','0002-12-02','-0001-11-30'])) {
					return Yii::$app->formatter->format($model->ended_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		$this->templateColumns['tags'] = 'tags';
		$this->templateColumns['tag_all'] = 'tag_all';
		$this->templateColumns['batchs'] = 'batchs';
		$this->templateColumns['batch_all'] = 'batch_all';
		$this->templateColumns['blasting_condition'] = 'blasting_condition';
		$this->templateColumns['blastings'] = 'blastings';
		$this->templateColumns['registered'] = 'registered';
		$this->templateColumns['confirm'] = 'confirm';
		$this->templateColumns['no_confirm'] = 'no_confirm';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert) 
	{
		if(parent::beforeSave($insert)) {
			$this->started_date = Yii::$app->formatter->asDate($this->started_date, 'php:Y-m-d');
			$this->ended_date = Yii::$app->formatter->asDate($this->ended_date, 'php:Y-m-d');
		}
		return true;	
	}

}
