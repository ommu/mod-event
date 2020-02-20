<?php
/**
 * EventBlastingHistory

 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 13:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_blasting_history".
 *
 * The followings are the available columns in table "ommu_event_blasting_history":
 * @property string $id
 * @property string $item_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property EventBlastingItem $item
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;

class EventBlastingHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	// Search Variable
	public $item_search;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_blasting_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['item_id', 'view_ip'], 'required'],
			[['item_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventBlastingItem::className(), 'targetAttribute' => ['item_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'item_id' => Yii::t('app', 'Item'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
			'item_search' => Yii::t('app', 'Item'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getItem()
	{
		return $this->hasOne(EventBlastingItem::className(), ['id' => 'item_id']);
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['item_search'] = [
			'attribute' => 'item_search',
			'value' => function($model, $key, $index, $column) {
				return $model->item->id;
			},
			'visible' => !Yii::$app->request->get('item') ? true : false,
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = 'view_ip';
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

}
