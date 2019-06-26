<?php
/**
 * EventCategory

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 12:12 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "_view_event_category".
 *
 * The followings are the available columns in table "_view_event_category":
 * @property integer $id
 * @property string $events
 * @property string $event_all
 *
 */

namespace ommu\event\models\view;

use Yii;
use yii\helpers\Url;

class EventCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_event_category';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'event_all'], 'integer'],
			[['events'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'Cat'),
			'events' => Yii::t('app', 'Events'),
			'event_all' => Yii::t('app', 'Event All'),
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
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['id'] = 'id';
		$this->templateColumns['events'] = 'events';
		$this->templateColumns['event_all'] = 'event_all';
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
