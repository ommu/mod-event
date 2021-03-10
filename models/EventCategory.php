<?php
/**
 * EventCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 November 2017, 09:44 WIB
 * @modified date 23 June 2019, 18:42 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_category".
 *
 * The followings are the available columns in table "ommu_event_category":
 * @property integer $id
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
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use app\models\Users;

class EventCategory extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['category_desc_i', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

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
			[['category_name_i'], 'required'],
			[['publish', 'category_name', 'category_desc', 'creation_id', 'modified_id'], 'integer'],
			[['category_name_i', 'category_desc_i'], 'string'],
			[['category_desc_i'], 'safe'],
			[['category_name_i'], 'string', 'max' => 64],
			[['category_desc_i'], 'string', 'max' => 128],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'Category'),
			'publish' => Yii::t('app', 'Publish'),
			'category_name' => Yii::t('app', 'Category'),
			'category_desc' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_name_i' => Yii::t('app', 'Category'),
			'category_desc_i' => Yii::t('app', 'Description'),
			'events' => Yii::t('app', 'Events'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvents($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(Events::className(), ['cat_id' => 'id'])
                ->alias('events')
                ->andOnCondition([sprintf('%s.publish', 'events') => $publish]);
        }

		$model = Events::find()
            ->alias('t')
            ->where(['t.cat_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$events = $model->count();

		return $events ? $events : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'category_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
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
	 * {@inheritdoc}
	 * @return \ommu\event\models\query\EventCategory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventCategory(get_called_class());
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
		$this->templateColumns['category_name_i'] = [
			'attribute' => 'category_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->category_name_i;
			},
		];
		$this->templateColumns['category_desc_i'] = [
			'attribute' => 'category_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->category_desc_i;
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
		$this->templateColumns['events'] = [
			'attribute' => 'events',
			'value' => function($model, $key, $index, $column) {
				$events = $model->getEvents(true);
				return Html::a($events, ['admin/manage', 'category' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} events', ['count' => $events])]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'html',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['setting/category/publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
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
	 * function getCategory
	 */
	public static function getCategory($publish=null, $array=true)
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.category_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.category_name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }

		$model = $model->orderBy('title.message ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'category_name_i');
        }

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->category_name_i = isset($this->title) ? $this->title->message : '';
		$this->category_desc_i = isset($this->description) ? $this->description->message : '';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        $module = strtolower(Yii::$app->controller->module->id);
        $controller = strtolower(Yii::$app->controller->id);
        $action = strtolower(Yii::$app->controller->action->id);

        $location = Inflector::slug($module.' '.$controller);

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->category_name)) {
                $category_name = new SourceMessage();
                $category_name->location = $location.'_title';
                $category_name->message = $this->category_name_i;
                if ($category_name->save()) {
                    $this->category_name = $category_name->id;
                }

            } else {
                $category_name = SourceMessage::findOne($this->category_name);
                $category_name->message = $this->category_name_i;
                $category_name->save();
            }

            if ($insert || (!$insert && !$this->category_desc)) {
                $category_desc = new SourceMessage();
                $category_desc->location = $location.'_description';
                $category_desc->message = $this->category_desc_i;
                if ($category_desc->save()) {
                    $this->category_desc = $category_desc->id;
                }

            } else {
                $category_desc = SourceMessage::findOne($this->category_desc);
                $category_desc->message = $this->category_desc_i;
                $category_desc->save();
            }
        }
        return true;
	}
}
