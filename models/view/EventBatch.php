<?php
/**
 * EventBatch

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 12:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "_view_event_batch".
 *
 * The followings are the available columns in table "_view_event_batch":
 * @property string $batch_id
 * @property string $advisers
 * @property string $adviser_all
 * @property string $registered
 * @property string $confirm
 * @property string $no_confirm
 *
 */

namespace ommu\event\models\view;

use Yii;
use yii\helpers\Url;

class EventBatch extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_view_event_batch';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey() {
		return ['batch_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['batch_id', 'adviser_all', 'registered'], 'integer'],
			[['advisers', 'confirm', 'no_confirm'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'batch_id' => Yii::t('app', 'Batch'),
			'advisers' => Yii::t('app', 'Advisers'),
			'adviser_all' => Yii::t('app', 'Adviser All'),
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
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['batch_id'] = 'batch_id';
		$this->templateColumns['advisers'] = 'advisers';
		$this->templateColumns['adviser_all'] = 'adviser_all';
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

}
