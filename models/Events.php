<?php
/**
 * Events
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:19 WIB
 * @modified date 24 June 2019, 08:55 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_events".
 *
 * The followings are the available columns in table "ommu_events":
 * @property integer $id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $title
 * @property string $theme
 * @property string $introduction
 * @property string $description
 * @property string $cover_filename
 * @property string $banner_filename
 * @property integer $registered_enable
 * @property string $registered_message
 * @property string $registered_type
 * @property string $package_reward
 * @property integer $enable_filter
 * @property string $published_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property EventBatch[] $batches
 * @property EventFilterGender[] $genders
 * @property EventFilterMajor[] $majors
 * @property EventFilterMajorGroup[] $majorGroups
 * @property EventFilterUniversity[] $universities
 * @property EventRegistered[] $registereds
 * @property EventTag[] $tags
 * @property EventCategory $category
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use ommu\users\models\Users;
use yii\base\Event;

class Events extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['theme', 'introduction', 'description', 'cover_filename', 'banner_filename', 'registered_message', 'registered_type', 'package_reward', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'tag', 'gender', 'major', 'majorGroup', 'university', 'batches', 'registereds'];

	public $old_cover_filename;
	public $old_banner_filename;
	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $tag;
	public $gender;
	public $major;
	public $majorGroup;
	public $university;

	const SCENARIO_FILTER = 'filterForm';

	const EVENT_BEFORE_SAVE_EVENTS = 'BeforeSaveEvents';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'title', 'introduction', 'description', 'published_date'], 'required'],
			[['enable_filter'], 'required', 'on' => self::SCENARIO_FILTER],
			[['publish', 'cat_id', 'registered_enable', 'enable_filter', 'creation_id', 'modified_id'], 'integer'],
			[['introduction', 'description', 'registered_message', 'registered_type'], 'string'],
			//[['registered_message', 'package_reward'], 'serialize'],
			[['theme', 'cover_filename', 'banner_filename', 'registered_enable', 'registered_message', 'registered_type', 'package_reward', 'tag', 'gender', 'major', 'majorGroup', 'university'], 'safe'],
			[['title'], 'string', 'max' => 64],
			[['theme'], 'string', 'max' => 128],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_FILTER] = ['enable_filter', 'gender', 'major', 'majorGroup', 'university'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'Event'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Event'),
			'theme' => Yii::t('app', 'Theme'),
			'introduction' => Yii::t('app', 'Introduction'),
			'description' => Yii::t('app', 'Description'),
			'cover_filename' => Yii::t('app', 'Cover'),
			'banner_filename' => Yii::t('app', 'Banner'),
			'registered_enable' => Yii::t('app', 'Registered Online'),
			'registered_message' => Yii::t('app', 'Registered Message'),
			'registered_type' => Yii::t('app', 'Registered Type'),
			'package_reward' => Yii::t('app', 'Package Reward'),
			'package_reward[type]' => Yii::t('app', 'Type'),
			'package_reward[reward]' => Yii::t('app', 'Reward'),
			'enable_filter' => Yii::t('app', 'Registered Filter'),
			'published_date' => Yii::t('app', 'Published Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_cover_filename' => Yii::t('app', 'Old Cover'),
			'old_banner_filename' => Yii::t('app', 'Old Banner'),
			'batches' => Yii::t('app', 'Batches'),
			'registereds' => Yii::t('app', 'Registereds'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'tag' => Yii::t('app', 'Tags'),
			'gender' => Yii::t('app', 'Gender'),
			'major' => Yii::t('app', 'Majors'),
			'majorGroup' => Yii::t('app', 'Major Groups'),
			'university' => Yii::t('app', 'Universities'),
		];
	}

	/**
	 * @param $type relation|array|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getBatches($type='relation', $publish=1)
	{
		if($type == 'relation')
			return $this->hasMany(EventBatch::className(), ['event_id' => 'id'])
				->alias('batches')
				->andOnCondition([sprintf('%s.publish', 'batches') => $publish]);

		if($type == 'array')
			return \yii\helpers\ArrayHelper::map($this->batches, 'id', 'batch_name');

		$model = EventBatch::find()
			->where(['event_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$batches = $model->count();

		return $batches ? $batches : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGenders($result=false)
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->genders, 'gender', 'id');

		return $this->hasMany(EventFilterGender::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMajors($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->majors, 'major_id', $val=='id' ? 'id' : 'major.major_name');

		return $this->hasMany(EventFilterMajor::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMajorGroups($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->majorGroups, 'major_group_id', $val=='id' ? 'id' : 'group.group_name');

		return $this->hasMany(EventFilterMajorGroup::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUniversities($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->universities, 'university_id', $val=='id' ? 'id' : 'university.company.company_name');

		return $this->hasMany(EventFilterUniversity::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistereds($count=false)
	{
		if($count == false)
			return $this->hasMany(EventRegistered::className(), ['event_id' => 'id']);

		$model = EventRegistered::find()
			->where(['event_id' => $this->id]);
		$registereds = $model->count();

		return $registereds ? $registereds : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->tags, 'tag_id', $val=='id' ? 'id' : 'tag.body');

		return $this->hasMany(EventTag::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(EventCategory::className(), ['id' => 'cat_id']);
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
	 * @return \ommu\event\models\query\Events the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\Events(get_called_class());
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
					// return $model->categoryName;
				},
				'filter' => EventCategory::getCategory(),
			];
		}
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['introduction'] = [
			'attribute' => 'introduction',
			'value' => function($model, $key, $index, $column) {
				return $model->introduction;
			},
			'format' => 'html',
		];
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
				return $model->description;
			},
			'format' => 'html',
		];
		$this->templateColumns['cover_filename'] = [
			'attribute' => 'cover_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->id]);
				return $model->cover_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover_filename])), ['alt'=>$model->cover_filename]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['banner_filename'] = [
			'attribute' => 'banner_filename',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->id]);
				return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['alt'=>$model->banner_filename]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['tag'] = [
			'attribute' => 'tag',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getTags(true, 'title'));
			},
		];
		$this->templateColumns['theme'] = [
			'attribute' => 'theme',
			'value' => function($model, $key, $index, $column) {
				return $model->theme;
			},
		];
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->published_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'published_date'),
		];
		$this->templateColumns['registered_type'] = [
			'attribute' => 'registered_type',
			'value' => function($model, $key, $index, $column) {
				return self::getRegisteredType($model->registered_type);
			},
			'filter' => self::getRegisteredType(),
		];
		$this->templateColumns['registered_message'] = [
			'attribute' => 'registered_message',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->registered_message);
			},
			'format' => 'html',
		];
		$this->templateColumns['package_reward'] = [
			'attribute' => 'package_reward',
			'value' => function($model, $key, $index, $column) {
				return $model->isFree == true ? Yii::t('app', 'Free') : Events::parseReward($model->package_reward);
			},
		];
		$this->templateColumns['gender'] = [
			'attribute' => 'gender',
			'value' => function($model, $key, $index, $column) {
				return self::parseGender(array_flip($model->getGenders(true)), ', ');
			},
			'filter' => EventFilterGender::getGender(),
		];
		$this->templateColumns['major'] = [
			'attribute' => 'major',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getMajors(true, 'title'));
			},
		];
		$this->templateColumns['majorGroup'] = [
			'attribute' => 'majorGroup',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getMajorGroups(true, 'title'));
			},
		];
		$this->templateColumns['university'] = [
			'attribute' => 'university',
			'value' => function($model, $key, $index, $column) {
				return implode(', ', $model->getUniversities(true, 'title'));
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
					// return $model->creationDisplayname;
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
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['batches'] = [
			'attribute' => 'batches',
			'value' => function($model, $key, $index, $column) {
				$batches = $model->getBatches('count');
				return Html::a($batches, ['o/batch/manage', 'event'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} batches', ['count'=>$batches])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['registereds'] = [
			'attribute' => 'registereds',
			'value' => function($model, $key, $index, $column) {
				$registereds = $model->getRegistereds(true);
				return Html::a($registereds, ['registered/admin/manage', 'event'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} registereds', ['count'=>$registereds])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['enable_filter'] = [
			'attribute' => 'enable_filter',
			'label' => Yii::t('app', 'Filter'),
			'value' => function($model, $key, $index, $column) {
				return $this->getRegisteredEnable($model->enable_filter);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['registered_enable'] = [
			'attribute' => 'registered_enable',
			'label' => Yii::t('app', 'Registered'),
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['registered', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->registered_enable, 'Enable,Disable');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getIsFree
	 */
	public function getIsFree()
	{
		if(!$this->package_reward)
			return false;

		return $this->package_reward['type'] == '1' && $this->package_reward['reward'] == '100' ? true : false;
	}

	/**
	 * function getIsPackage
	 */
	public function getIsPackage()
	{
		$batches = $this->getBatches('count');
		return $batches == 1 || ($batches > 1 && $this->registered_type == 'package') ? true : false;
	}

	/**
	 * function getRegisteredEnable
	 */
	public static function getRegisteredEnable($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Enable'),
			0 => Yii::t('app', 'Disable'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getRegisteredEnable
	 */
	public static function getPackageRewardType($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Reward (%)'),
			0 => Yii::t('app', 'Reward (Price)'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getRegisteredType
	 */
	public static function getRegisteredType($value=null)
	{
		$items = array(
			'single' => Yii::t('app', 'Single'),
			'multiple' => Yii::t('app', 'Multiple'),
			'package' => Yii::t('app', 'Package'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@public/event') : 'event');
	}

	/**
	 * function parseGender
	 */
	public static function parseGender($gender, $sep='li')
	{
		if(!is_array($gender) || (is_array($gender) && empty($gender)))
			return '-';

		$genders = EventFilterGender::getGender();
		$items = [];
		foreach ($gender as $val) {
			if(array_key_exists($val, $genders))
				$items[] = $genders[$val];
		}

		if($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class'=>'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * function parseReward
	 */
	public static function parseReward($reward)
	{
		if(!$reward)
			return '-';
		
		return Yii::t('app', 'Reward {reward}', ['reward' => $reward['type'] == 1 ? $reward['reward'].'%' : Yii::$app->formatter->asCurrency($reward['reward'])]);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_cover_filename = $this->cover_filename;
		$this->old_banner_filename = $this->banner_filename;
		$this->registered_message = unserialize($this->registered_message);
		$this->package_reward = unserialize($this->package_reward);
		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$this->tag = implode(',', $this->getTags(true, 'title'));
		$this->gender = array_flip($this->getGenders(true));
		$this->major = implode(',', $this->getMajors(true, 'title'));
		$this->majorGroup = implode(',', $this->getMajorGroups(true, 'title'));
		$this->university = implode(',', $this->getUniversities(true, 'title'));
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			// $this->cover_filename = UploadedFile::getInstance($this, 'cover_filename');
			if($this->cover_filename instanceof UploadedFile && !$this->cover_filename->getHasError()) {
				$coverFilenameFileType = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
				if(!in_array(strtolower($this->cover_filename->getExtension()), $coverFilenameFileType)) {
					$this->addError('cover_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$this->cover_filename->name,
						'extensions'=>$this->formatFileType($coverFilenameFileType, false),
					]));
				}
			} else {
				if($this->isNewRecord || (!$this->isNewRecord && $this->old_cover_filename == ''))
					$this->addError('cover_filename', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('cover_filename')]));
			}

			// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
			if($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
				$bannerFilenameFileType = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
				if(!in_array(strtolower($this->banner_filename->getExtension()), $bannerFilenameFileType)) {
					$this->addError('banner_filename', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$this->banner_filename->name,
						'extensions'=>$this->formatFileType($bannerFilenameFileType, false),
					]));
				}
			}

			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			if($this->registered_enable) {
				if($this->registered_message == '')
					$this->addError('registered_message', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('registered_message')]));
				if($this->registered_type == '')
					$this->addError('registered_type', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('registered_type')]));
			}

			// validate and set package reward
			if($this->scenario != self::SCENARIO_FILTER) {
				if($this->package_reward['type'] == 1 && $this->package_reward['reward'] > 100)
					$this->addError('package_reward', Yii::t('app', '{attribute} max 100%.', ['attribute'=>$this->getAttributeLabel('package_reward')]));

				if($this->package_reward['type'] == '' || $this->package_reward['reward'] == '')
					$this->package_reward = '';
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
			if(!$insert) {
				$uploadPath = join('/', [self::getUploadPath(), $this->id]);
				$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
				$this->createUploadDirectory(self::getUploadPath(), $this->id);

				// $this->cover_filename = UploadedFile::getInstance($this, 'cover_filename');
				if($this->cover_filename instanceof UploadedFile && !$this->cover_filename->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->cover_filename->getExtension()); 
					if($this->cover_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
						if($this->old_cover_filename != '' && file_exists(join('/', [$uploadPath, $this->old_cover_filename])))
							rename(join('/', [$uploadPath, $this->old_cover_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_cover_filename]));
						$this->cover_filename = $fileName;
					}
				} else {
					if($this->cover_filename == '')
						$this->cover_filename = $this->old_cover_filename;
				}

				// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
				if($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
					$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->banner_filename->getExtension()); 
					if($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName]))) {
						if($this->old_banner_filename != '' && file_exists(join('/', [$uploadPath, $this->old_banner_filename])))
							rename(join('/', [$uploadPath, $this->old_banner_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_banner_filename]));
						$this->banner_filename = $fileName;
					}
				} else {
					if($this->banner_filename == '')
						$this->banner_filename = $this->old_banner_filename;
				}

				// set filters
				$event = new Event(['sender' => $this]);
				Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_EVENTS, $event);
			}

			$this->registered_message = serialize($this->registered_message);
			$this->package_reward = serialize($this->package_reward);
			$this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');
		}
		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		$uploadPath = join('/', [self::getUploadPath(), $this->id]);
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
		$this->createUploadDirectory(self::getUploadPath(), $this->id);

		if($insert) {
			// $this->cover_filename = UploadedFile::getInstance($this, 'cover_filename');
			if($this->cover_filename instanceof UploadedFile && !$this->cover_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->cover_filename->getExtension()); 
				if($this->cover_filename->saveAs(join('/', [$uploadPath, $fileName])))
					self::updateAll(['cover_filename' => $fileName], ['id' => $this->id]);
			}

			// $this->banner_filename = UploadedFile::getInstance($this, 'banner_filename');
			if($this->banner_filename instanceof UploadedFile && !$this->banner_filename->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->banner_filename->getExtension()); 
				if($this->banner_filename->saveAs(join('/', [$uploadPath, $fileName])))
					self::updateAll(['banner_filename' => $fileName], ['id' => $this->id]);
			}

			// set filters
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_EVENTS, $event);
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$uploadPath = join('/', [self::getUploadPath(), $this->id]);
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

		if($this->cover_filename != '' && file_exists(join('/', [$uploadPath, $this->cover_filename])))
			rename(join('/', [$uploadPath, $this->cover_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->cover_filename]));

		if($this->banner_filename != '' && file_exists(join('/', [$uploadPath, $this->banner_filename])))
			rename(join('/', [$uploadPath, $this->banner_filename]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->banner_filename]));

	}
}
