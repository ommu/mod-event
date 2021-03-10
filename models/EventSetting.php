<?php
/**
 * EventSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 November 2017, 09:36 WIB
 * @modified date 23 June 2019, 15:30 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_setting".
 *
 * The followings are the available columns in table "ommu_event_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $event_price
 * @property string $event_agreement
 * @property string $event_warning_message
 * @property string $event_notify_diff_type
 * @property integer $event_notify_difference
 * @property string $event_banned_diff_type
 * @property integer $event_banned_difference
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use app\models\Users;

class EventSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_keyword', 'meta_description', 'event_price', 'event_agreement', 'event_warning_message', 'event_notify_diff_type', 'event_notify_difference', 'event_banned_diff_type', 'event_banned_difference'], 'required'],
			[['permission', 'event_price', 'event_notify_difference', 'event_banned_difference', 'modified_id'], 'integer'],
			[['meta_keyword', 'meta_description', 'event_agreement', 'event_notify_diff_type', 'event_banned_diff_type'], 'string'],
			//[['event_warning_message'], 'serialize'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'event_price' => Yii::t('app', 'Event Price'),
			'event_agreement' => Yii::t('app', 'Event Agreement'),
			'event_warning_message' => Yii::t('app', 'Event Warning Message'),
			'event_notify_diff_type' => Yii::t('app', 'Event Notify Diff Type'),
			'event_notify_difference' => Yii::t('app', 'Event Notify Difference'),
			'event_banned_diff_type' => Yii::t('app', 'Event Banned Diff Type'),
			'event_banned_difference' => Yii::t('app', 'Event Banned Difference'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
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

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['event_price'] = [
			'attribute' => 'event_price',
			'value' => function($model, $key, $index, $column) {
				return $model->event_price;
			},
		];
		$this->templateColumns['event_agreement'] = [
			'attribute' => 'event_agreement',
			'value' => function($model, $key, $index, $column) {
				return $model->event_agreement;
			},
		];
		$this->templateColumns['event_warning_message'] = [
			'attribute' => 'event_warning_message',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->event_warning_message);
			},
		];
		$this->templateColumns['event_notify_diff_type'] = [
			'attribute' => 'event_notify_diff_type',
			'value' => function($model, $key, $index, $column) {
				return self::getEventNotifyDiffType($model->event_notify_diff_type);
			},
			'filter' => self::getEventNotifyDiffType(),
		];
		$this->templateColumns['event_notify_difference'] = [
			'attribute' => 'event_notify_difference',
			'value' => function($model, $key, $index, $column) {
				return $model->event_notify_difference;
			},
		];
		$this->templateColumns['event_banned_diff_type'] = [
			'attribute' => 'event_banned_diff_type',
			'value' => function($model, $key, $index, $column) {
				return self::getEventNotifyDiffType($model->event_banned_diff_type);
			},
			'filter' => self::getEventNotifyDiffType(),
		];
		$this->templateColumns['event_banned_difference'] = [
			'attribute' => 'event_banned_difference',
			'value' => function($model, $key, $index, $column) {
				return $model->event_banned_difference;
			},
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
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => 1])->one();
            return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$moduleName = "module name";
		$module = strtolower(Yii::$app->controller->module->id);
        if (($module = Yii::$app->moduleManager->getModule($module)) != null) {
            $moduleName = strtolower($module->getName());
        }

		$items = array(
			1 => Yii::t('app', 'Yes, the public can view {module} unless they are made private.', ['module' => $moduleName]),
			0 => Yii::t('app', 'No, the public cannot view {module}.', ['module' => $moduleName]),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * function getEventNotifyDiffType
	 */
	public static function getEventNotifyDiffType($value=null)
	{
		$items = array(
			'1' => Yii::t('app', 'Hour'),
			'2' => Yii::t('app', 'Day'),
			'3' => Yii::t('app', 'Week'),
			'4' => Yii::t('app', 'Month'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->event_warning_message = unserialize($this->event_warning_message);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if (!$this->isNewRecord) {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        if (parent::beforeSave($insert)) {
			$this->event_warning_message = serialize($this->event_warning_message);
        }
        return true;
	}
}
