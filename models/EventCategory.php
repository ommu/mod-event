<?php
/**
 * EventCategory
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 09:44 WIB
 * @modified date 23 June 2019, 18:42 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_category".
 *
 * The followings are the available columns in table "ommu_event_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $category_name
 * @property integer $category_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Events[] $events
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use app\models\SourceMessage;
use ommu\users\models\Users;

class EventCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $category_name_i;
	public $category_desc_i;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['category_name_i', 'category_desc_i'], 'required'],
			[['publish', 'category_name', 'category_desc', 'creation_id', 'modified_id'], 'integer'],
			[['creation_id', 'creation_date', 'modified_id', 'modified_date', 'updated_date'], 'safe'],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvents()
	{
		return $this->hasMany(Events::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getName()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'category_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDesc()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'category_desc']);
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'cat_id' => Yii::t('app', 'Cat'),
			'publish' => Yii::t('app', 'Publish'),
			'category_name' => Yii::t('app', 'Category Name'),
			'category_desc' => Yii::t('app', 'Category Desc'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'category_name_i' => Yii::t('app', 'Category Name'),
			'category_desc_i' => Yii::t('app', 'Category Desc'),
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
		$this->templateColumns['category_name_i'] = [
			'attribute' => 'category_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->category_name ? $model->name->message : '-';
			},
		];
		$this->templateColumns['category_desc_i'] = [
			'attribute' => 'category_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->category_desc ? $model->desc->message : '-';
			},
		];
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->updated_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['setting/category/publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * function getCategory
	 */
	public static function getCategory($publish=null) 
	{
		$items = [];
		$model = self::find();
		if ($publish!=null)
			$model = $model->andWhere(['publish'=>$publish]);
		$model = $model->orderBy('category_name ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->cat_id] = $val->name->message;
			}
		}
		
		return $items;
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
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);
		$location = Utility::getUrlTitle($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			// Create action
			if($this->isNewRecord || (!$this->isNewRecord && !$this->category_name)) {
				$category_name = new SourceMessage();
				$category_name->location = $location.'_category_name';
				$category_name->message = $this->category_name_i;
				if($category_name->save())
					$this->category_name = $category_name->id;
				
			} else {
				$category_name = SourceMessage::findOne($this->category_name);
				$category_name->message = $this->category_name_i;
				$category_name->save();
			}

			if($this->isNewRecord || (!$this->isNewRecord && !$this->category_desc)) {
				$category_desc = new SourceMessage();
				$category_desc->location = $location.'_category_desc';
				$category_desc->message = $this->category_desc_i;
				if($category_desc->save())
					$this->category_desc = $category_desc->id;
				
			} else {
				$category_desc = SourceMessage::findOne($this->category_desc);
				$category_desc->message = $this->category_desc_i;
				$category_desc->save();
			}
		}
		return true;	
	}

	// /**
	//  * after validate attributes
	//  */
	// public function afterValidate()
	// {
	// 	parent::afterValidate();
	// 	// Create action
		
	// 	return true;
	// }
	
	// /**
	//  * After save attributes
	//  */
	// public function afterSave($insert, $changedAttributes) 
	// {
	// 	parent::afterSave($insert, $changedAttributes);
	// 	// Create action
	// }

	// /**
	//  * Before delete attributes
	//  */
	// public function beforeDelete() 
	// {
	// 	if(parent::beforeDelete()) {
	// 		// Create action
	// 	}
	// 	return true;
	// }

	// /**
	//  * After delete attributes
	//  */
	// public function afterDelete() 
	// {
	// 	parent::afterDelete();
	// 	// Create action
	// }
}
