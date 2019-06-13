<?php
/**
 * Events

 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:19 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_events".
 *
 * The followings are the available columns in table "ommu_events":
 * @property string $event_id
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
 * @property integer $enable_filter
 * @property string $published_date
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property EventBatch[] $batches
 * @property EventBlastings[] $blastings
 * @property EventFilterMajor[] $majors
 * @property EventFilterUniversity[] $universities
 * @property EventFilterGender[] $filters
 * @property EventTag[] $tags
 * @property EventCategory $category
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use app\modules\ipedia\models\IpediaAnother;
use app\modules\ipedia\models\IpediaMajor;
use ommu\event\models\view\Events as EventsView;
use app\models\CoreTags;

class Events extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	// Include semua fungsi yang ada pada traits FileSystem;
	use \app\components\traits\FileSystem;

	public $gridForbiddenColumn = ['introduction', 'theme', 'description','cover_filename', 'banner_filename', 'registered_message', 'registered_type', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	// Search Variable
	public $category_search;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $old_cover;
	public $old_banner;
	public $tag_search;
	public $tag_id_i;
	public $tag_hidden;
	public $filter_gender;
	public $filter_major;
	public $major_hidden;
	public $filter_university;
	public $university_hidden;
	public $blasting_search;

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
			[['publish', 'cat_id', 'registered_enable', 'enable_filter', 'creation_id', 'modified_id'], 'integer'],
			[['cat_id', 'title', 'theme', 'introduction', 'description', 'published_date', 'creation_id'], 'required'],
			// [['introduction', 'description', 'cover_filename', 'banner_filename', 'registered_message', 'registered_type'], 'string'],
			[['cover_filename', 'banner_filename'], 'required', 'on' => 'formCreate'],
			[['introduction', 'description', 'registered_message', 'registered_type'], 'string'],
			[['tag_hidden', 'tag_id_i', 'cover_filename', 'banner_filename', 'registered_message', 'registered_type', 'published_date', 'creation_date', 'modified_id', 'modified_date', 'updated_date', 'filter_gender', 'major_hidden', 'filter_major', 'university_hidden'], 'safe'],
			[['title'], 'string', 'max' => 64],
			[['theme'], 'string', 'max' => 128],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
			[['cover_filename'], 'file', 'extensions' => 'jpeg, jpg, png, bmp, gif'],
			[['banner_filename'], 'file', 'extensions' => 'jpeg, jpg, png, bmp, gif'],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBatches()
	{
		return $this->hasMany(EventBatch::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBlastings()
	{
		return $this->hasMany(EventBlastings::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMajors()
	{
		return $this->hasMany(EventFilterMajor::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUniversities()
	{
		return $this->hasMany(EventFilterUniversity::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFilters()
	{
		return $this->hasMany(EventFilterGender::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->hasMany(EventTag::className(), ['event_id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(EventCategory::className(), ['cat_id' => 'cat_id']);
	}

	public function getView()
	{
		return $this->hasOne(EventsView::className(), ['event_id' => 'event_id']);
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
			'event_id' => Yii::t('app', 'Event'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'title' => Yii::t('app', 'Title'),
			'theme' => Yii::t('app', 'Theme'),
			'introduction' => Yii::t('app', 'Introduction'),
			'description' => Yii::t('app', 'Description'),
			'cover_filename' => Yii::t('app', 'Cover Filename'),
			'banner_filename' => Yii::t('app', 'Banner Filename'),
			'registered_enable' => Yii::t('app', 'Registered Enable'),
			'registered_message' => Yii::t('app', 'Registered Message'),
			'registered_type' => Yii::t('app', 'Registered Type'),
			'enable_filter' => Yii::t('app', 'Enable Filter'),
			'published_date' => Yii::t('app', 'Published Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'category_search' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'tag_search' => Yii::t('app', 'Tags'),
			'tag_id_i' => Yii::t('app', 'Tags'),
			'filter_gender' => Yii::t('app', 'Gender'),
			'filter_major' => Yii::t('app', 'Major'),
			'filter_university' => Yii::t('app', 'University'),
			'blasting_search' => Yii::t('app', 'Blasting'),
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
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['category_search'] = [
				'attribute' => 'category_search',
				'value' => function($model, $key, $index, $column) {
					return $model->category->name->message;
				},
			];
		}
		$this->templateColumns['title'] = 'title';
		$this->templateColumns['theme'] = 'theme';
		// $this->templateColumns['introduction'] = 'introduction';
		$this->templateColumns['introduction'] = [
			'attribute' => 'introduction',
			'value' => function($model, $key, $index, $column) {
					return $model->introduction ? $model->introduction : '-';
				},
			'format' => 'html',
		];
		// $this->templateColumns['description'] = 'description';
		$this->templateColumns['description'] = [
			'attribute' => 'description',
			'value' => function($model, $key, $index, $column) {
					return  $model->description ? $model->description : '-';
				},
			'format' => 'html',
		];
		$this->templateColumns['cover_filename'] = 'cover_filename';
		$this->templateColumns['banner_filename'] = 'banner_filename';
		// $this->templateColumns['registered_message'] = 'registered_message';
		$this->templateColumns['registered_message'] =  [
			'attribute' => 'registered_message',
			'value' => function($model, $key, $index, $column) {
					return $model->registered_message ? $model->registered_message : '-';
				},
			'format' => 'html',
		];
		// $this->templateColumns['registered_type'] = 'registered_type';
		$this->templateColumns['registered_type'] = [
			'attribute' => 'registered_type',
			'filter'=>array("single"=>"Single", "multiple"=>"Multiple", "package"=>"Package"),
			'value' => function($model, $key, $index, $column) {
				return $model->registered_type;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['published_date'] = [
			'attribute' => 'published_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'published_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->published_date, 
					['0000-00-00','1970-01-01','0002-12-02','-0001-11-30'])) {
					return Yii::$app->formatter->format($model->published_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		$this->templateColumns['tag_search'] = [
			'attribute' => 'tag_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['tag/index', 'event'=>$model->primaryKey]);
				return Html::a($model->view->tags ? $model->view->tags : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->creation_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation->displayname) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->modified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified->displayname) ? $model->modified->displayname : '-';
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
		$this->templateColumns['registered_enable'] = [
			'attribute' => 'registered_enable',
			// 'value' => function($model, $key, $index, $column) {
			// 	return $model->registered_enable;
			// },
			'filter'=>array(0=>"Offline", 1=>"Online"),
			'value' => function($model, $key, $index, $column) {
				return $model->registered_enable ? Yii::t('app', 'Online') : Yii::t('app', 'Offline');
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['enable_filter'] = [
			'attribute' => 'enable_filter',
			'value' => function($model, $key, $index, $column) {
				return $model->enable_filter ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['blasting_search'] = [
			'attribute' => 'blasting_search',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['blastings/index', 'event_id'=>$model->primaryKey]);
				return  Html::a($model->view->blasting_condition ? Yii::t('app', 'Yes') : Yii::t('app', 'No'), $url);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
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
	 * function getEvent
	 */
	public static function getEvent($publish=null) 
	{
		$items = [];
		$model = self::find();
		if ($publish!=null)
			$model = $model->andWhere(['publish'=>$publish]);
		$model = $model->orderBy('title ASC')->all();

		if($model !== null) {
			foreach($model as $val) {
				$items[$val->event_id] = $val->title;
			}
		}
		
		return $items;
	}

	/**
	 * Mengembalikan lokasi cover
	 *
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getCoverPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/event/admin/cover') : 'public/event/admin/cover');
	}

	/**
	 * Mengembalikan lokasi banner
	 *
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getBannerPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@webroot/public/event/admin/banner') : 'public/event/admin/banner');
	}

	/**
	 * afterFind
	 *
	 * Simpan nama cover lama untuk keperluan jikalau kondisi update tp covernya tidak diupdate.
	 */
	public function afterFind() 
	{
		$this->old_cover = $this->cover_filename;
		$this->old_banner = $this->banner_filename;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';

			if ($this->registered_enable == 0 && $this->registered_message == null) {
				$this->addError('registered_message', Yii::t('app', 'Registered Message harus diisi.'));
			} else if ($this->registered_enable == 1 && $this->registered_type == null) {
				$this->addError('registered_type', Yii::t('app', 'Registered Type harus diisi.'));
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
			$this->published_date = Yii::$app->formatter->asDate($this->published_date, 'php:Y-m-d');

			// cover path
			$coverPath = Yii::getAlias('@webroot/public/event/admin/cover');
			$bannerPath = Yii::getAlias('@webroot/public/event/admin/banner');

			// Add directory cover
			if(!file_exists($coverPath)) {
				@mkdir($coverPath, 0755,true);

				// Add file in directory (index.php)
				$indexFile = join('/', [$coverPath, 'index.php']);
				if(!file_exists($indexFile)) {
					file_put_contents($indexFile, "<?php\n");
				}

			}else {
				@chmod($coverPath, 0755,true);
			}

			// Add directory banner
			if(!file_exists($bannerPath)) {
				@mkdir($bannerPath, 0755,true);

				// Add file in directory (index.php)
				$indexFile = join('/', [$bannerPath, 'index.php']);
				if(!file_exists($indexFile)) {
					file_put_contents($indexFile, "<?php\n");
				}

			}else {
				@chmod($bannerPath, 0755,true);
			}

			// Upload cover
			if($this->cover_filename instanceof \yii\web\UploadedFile) {
				$imageName = time().'_'.$this->sanitizeFileName($this->title).'.'. $this->cover_filename->extension; 
				if($this->cover_filename->saveAs($coverPath.'/'.$imageName)) {
					$this->cover_filename = $imageName;
					@chmod($imageName, 0777);
				}
			}

			// Upload banner
			if($this->banner_filename instanceof \yii\web\UploadedFile) {
				$imageName = time().'_'.$this->sanitizeFileName($this->title).'.'. $this->banner_filename->extension; 
				if($this->banner_filename->saveAs($bannerPath.'/'.$imageName)) {
					$this->banner_filename = $imageName;
					@chmod($imageName, 0777);
				}
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
	
	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes) 
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		parent::afterSave($insert, $changedAttributes);
		// jika cover diperbarui, hapus cover yg lama.
		if(!$insert && $this->cover_filename != $this->old_cover) {
			$fname = join('/', [self::getCoverPath(), $this->old_cover]);
			if(file_exists($fname)) {
				@unlink($fname);
			}
		}

		// jika banner diperbarui, hapus cover yg lama.
		if(!$insert && $this->banner_filename != $this->old_banner) {
			$fname = join('/', [self::getBannerPath(), $this->old_banner]);
			if(file_exists($fname)) {
				@unlink($fname);
			}
		}

		//menyimpan tag di table coretags dan event tag
		if($action == 'create' && $this->tag_hidden)
		{	
			$arrayTag = explode(',', $this->tag_hidden);
			if (count($arrayTag)>0){
				foreach ($arrayTag as $value) {
					// mengecek apakah tag yang sama sudah ada sebelumnya
					$tag_id = CoreTags::find()->where(['body' => trim($value)])->one();
					if (trim($value) != '') {
						// jika belum buat tag baru
						if ($tag_id == null) {
							$tag_id = new CoreTags();
							$tag_id->body = trim($value);
							$tag_id->save(false);
						} 					
						$model = new EventTag();
						$model->event_id = $this->event_id;
						$model->tag_id = $tag_id->tag_id;
						
						$model->save();
					}
				}	
			}
		} else if ($action == 'update') {
			if (isset($this->tag_hidden)) {
				$arrayTag = explode(',', $this->tag_hidden);
				$tag = EventTag::find()->select(['id', 'tag_id'])->where(['event_id' => $this->event_id, 'publish' => 1])->all();
				if ($tag != null) {
					//delete if not in array
				 	$arrIdToBeDel = array();
			        $arrIdTagNotInsert = array();

					foreach($tag as $val) {
						if(!in_array( (string)$val->tag->body, $arrayTag)) {
							$arrIdToBeDel[] = $val->id;
						}
						if(in_array( (string)$val->tag->body, $arrayTag)) {
							$arrIdTagNotInsert[] = $val->tag->body;
						}
					}

					if(count($arrIdToBeDel) > 0) {
						$listIdToBeDel = implode(',', $arrIdToBeDel);
						EventTag::updateAll(['publish' => 2], "id IN ($listIdToBeDel)");
					}

					//insert new input
					$newArr = array_values(array_diff($arrayTag, $arrIdTagNotInsert));	
					foreach ($newArr as $value) {
						// mengecek apakah tag yang sama sudah ada sebelumnya
						$tag_id = CoreTags::find()->where(['body' => trim($value)])->one();
						if (trim($value) != '') {
							// jika belum buat tag baru
							if ($tag_id == null) {
								$tag_id = new CoreTags();
								$tag_id->body = trim($value);
								$tag_id->save(false);
							} 		
							$model = EventTag::find()->where(['event_id' => $this->event_id, 'tag_id' => $tag_id->tag_id])->one();	
							if ($model == null) {		
								$model = new EventTag();
								$model->event_id = $this->event_id;
								$model->tag_id = $tag_id->tag_id;
							} 
							$model->publish = 1;
							
							$model->save();
						}
					}	
				} else {
					if (count($arrayTag)>0){
						foreach ($arrayTag as $value) {
							// mengecek apakah tag yang sama sudah ada sebelumnya
							$tag_id = CoreTags::find()->where(['body' => trim($value)])->one();
							if (trim($value) != '') {
								// jika belum buat tag baru
								if ($tag_id == null) {
									$tag_id = new CoreTags();
									$tag_id->body = trim($value);
									$tag_id->save(false);
								} 					
								$model = EventTag::find()->where(['event_id' => $this->event_id, 'tag_id' => $tag_id->tag_id])->one();	
								if ($model == null) {		
									$model = new EventTag();
									$model->event_id = $this->event_id;
									$model->tag_id = $tag_id->tag_id;
								} 
								$model->publish = 1;
								
								$model->save();
							}
						}	
					}
				}
			}
		}

		if ($action == 'create') {
			// cek jika enable filter dicentang
			if ($this->enable_filter == 1) {
				// gender
				$filter_gender = new EventFilterGender();

				$filter_gender->event_id = $this->event_id;
				$filter_gender->gender = $this->filter_gender;
				$filter_gender->save();

				// major
				$arrayMajor = explode(',', $this->major_hidden);
				
				// if (count($arrayMajor)>0){
				// 	foreach ($arrayMajor as $value) {
				// 		$filter_major = new EventFilterMajor();
				// 		$filter_major->event_id = $this->event_id;
				// 		$filter_major->major_id = $value;
						
				// 		$filter_major->save();
				// 	}	
				// }
				if (count($arrayMajor)>0){
					foreach ($arrayMajor as $value) {
						// mengecek apakah tag yang sama sudah ada sebelumnya
						$ipedia_another = IpediaAnother::find()->where(['another_name' => trim($value)])->one();
						if (trim($value) != '') {
							// jika belum buat tag baru
							if ($ipedia_another == null) {
								$ipedia_another = new IpediaAnother();
								$ipedia_another->another_name = trim($value);

								if ($ipedia_another->save(false)) {
									$ipedia_major = new IpediaMajor();
									$ipedia_major->another_id = $ipedia_another->another_id;
									$ipedia_major->save(false);
								}
							} else {
								$ipedia_major = IpediaMajor::find()->where(['another_id' => $ipedia_another->another_id])->one();
							}					

							$model = EventFilterMajor::find()->where(['event_id' => $this->event_id, 'major_id' => $ipedia_major->major_id])->one();	
							if ($model == null) {		
								$model = new EventFilterMajor();
								$model->event_id = $this->event_id;
								$model->major_id = $ipedia_major->major_id;
							} 
							$model->save();
						}
					}	
				}


				// university
				$arrayUniversity = explode(',', $this->university_hidden);
				
				if (count($arrayUniversity)>0){
					foreach ($arrayUniversity as $value) {
						$filter_university = new EventFilterUniversity();
						$filter_university->event_id = $this->event_id;
						$filter_university->university_id = $value;
						
						$filter_university->save();
					}	
				}
			}
		} else if ($action == 'update') {
			if ($this->enable_filter == 1) {
				// gender
				$filter_gender = EventFilterGender::find()->where(['event_id' => $this->event_id])->one();
				if ($this->filter_gender != null) {
					if ($filter_gender == null) {
						$filter_gender = new EventFilterGender();
						$filter_gender->event_id = $this->event_id;
					}
					$filter_gender->gender = $this->filter_gender;
					$filter_gender->save();
				} else {
					if ($filter_gender != null) {
						$filter_gender->delete();
					}
				}

				// major
				if (isset($this->major_hidden)) {
					$arrayMajor = explode(',', $this->major_hidden);
					$filter_major = EventFilterMajor::find()->select(['id', 'major_id'])->where(['event_id' => $this->event_id])->all();
					if ($filter_major != null) {
					 	//delete if not in array
					 	$arrIdToBeDel = array();
				        $arrIdMajorNotInsert = array();

						foreach($filter_major as $val) {
							if(!in_array( (string)$val->major->another->another_name, $arrayMajor)) {
								$arrIdToBeDel[] = $val->id;
							}
							if(in_array( (string)$val->major->another->another_name, $arrayMajor)) {
								$arrIdMajorNotInsert[] = $val->major->another->another_name;
							}
						}

						if(count($arrIdToBeDel) > 0) {
							$listIdToBeDel = implode(',', $arrIdToBeDel);
							EventFilterMajor::deleteAll("id IN ($listIdToBeDel)");
						}

						 //insert new input
						$newArr = array_values(array_diff($arrayMajor, $arrIdMajorNotInsert));						
						// foreach($newArr as $val) {
						// 	$model = new EventFilterMajor;
						// 	$model->event_id = $this->event_id;
						// 	$model->major_id = $val;
						// 	if(!$model->save()) {
						// 		print_r($model->getErrors());
						// 	}
						// }
						foreach ($newArr as $value) {
							// mengecek apakah major yang sama sudah ada sebelumnya
							$ipedia_another = IpediaAnother::find()->where(['another_name' => trim($value)])->one();
							if (trim($value) != '') {
								// jika belum buat tag baru
								if ($ipedia_another == null) {
									$ipedia_another = new IpediaAnother();
									$ipedia_another->another_name = trim($value);

									if ($ipedia_another->save(false)) {
										$ipedia_major = new IpediaMajor();
										$ipedia_major->another_id = $ipedia_another->another_id;
										$ipedia_major->save(false);
									}
								} else {
									$ipedia_major = IpediaMajor::find()->where(['another_id' => $ipedia_another->another_id])->one();
								}					

								$model = EventFilterMajor::find()->where(['event_id' => $this->event_id, 'major_id' => $ipedia_major->major_id])->one();	
								if ($model == null) {		
									$model = new EventFilterMajor();
									$model->event_id = $this->event_id;
									$model->major_id = $ipedia_major->major_id;
								} 
								$model->save();
							}
						}	
					} else {
						// if (count($arrayMajor)>0){
						// 	foreach ($arrayMajor as $value) {
						// 		$filter_major = new EventFilterMajor();
						// 		$filter_major->event_id = $this->event_id;
						// 		$filter_major->major_id = $value;
								
						// 		$filter_major->save();
						// 	}	
						// }
						if (count($arrayMajor)>0){
							foreach ($arrayMajor as $value) {
								// mengecek apakah tag yang sama sudah ada sebelumnya
								$ipedia_another = IpediaAnother::find()->where(['another_name' => trim($value)])->one();
								if (trim($value) != '') {
									// jika belum buat tag baru
									if ($ipedia_another == null) {
										$ipedia_another = new IpediaAnother();
										$ipedia_another->another_name = trim($value);

										if ($ipedia_another->save(false)) {
											$ipedia_major = new IpediaMajor();
											$ipedia_major->another_id = $ipedia_another->another_id;
											$ipedia_major->save(false);
										}
									} else {
										$ipedia_major = IpediaMajor::find()->where(['another_id' => $ipedia_another->another_id])->one();
									}					

									$model = EventFilterMajor::find()->where(['event_id' => $this->event_id, 'major_id' => $ipedia_major->major_id])->one();	
									if ($model == null) {		
										$model = new EventFilterMajor();
										$model->event_id = $this->event_id;
										$model->major_id = $ipedia_major->major_id;
									} 
									$model->save();
								}
							}	
						}
					}
				}

				// university
				if (isset($this->university_hidden)) {
					$arrayUniversity = explode(',', $this->university_hidden);
					$filter_university = EventFilterUniversity::find()->select(['id', 'university_id'])->where(['event_id' => $this->event_id])->all();
					if ($filter_university != null) {
					 	//delete if not in array
					 	$arrIdToBeDel = array();
				        $arrIdUniversityNotInsert = array();

						foreach($filter_university as $val) {
							if(!in_array( (string)$val->university_id, $arrayUniversity)) {
								$arrIdToBeDel[] = $val->id;
							}
							if(in_array( (string)$val->university_id, $arrayUniversity)) {
								$arrIdUniversityNotInsert[] = $val->university_id;
							}
						}

						if(count($arrIdToBeDel) > 0) {
							$listIdToBeDel = implode(',', $arrIdToBeDel);
							EventFilterUniversity::deleteAll("id IN ($listIdToBeDel)");
						}

						 //insert new input
						$newArr = array_values(array_diff($arrayUniversity, $arrIdUniversityNotInsert));						
						foreach($newArr as $val) {
							$model = new EventFilterUniversity;
							$model->event_id = $this->event_id;
							$model->university_id = $val;
							if(!$model->save()) {
								print_r($model->getErrors());
							}
						}
					} else {
						if (count($arrayUniversity)>0){
							foreach ($arrayUniversity as $value) {
								$filter_university = new EventFilterUniversity();
								$filter_university->event_id = $this->event_id;
								$filter_university->university_id = $value;
								
								$filter_university->save();
							}	
						}
					}
				}
			}
		}
	}

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

	/**
	 * After delete attributes
	 */
	public function afterDelete() 
	{
		parent::afterDelete();
		// Create action

		$fname = join('/', [self::getCoverPath(), $this->cover_filename]);
		if(file_exists($fname)) {
			@unlink($fname);
		}

		$fname = join('/', [self::getBannerPath(), $this->banner_filename]);
		if(file_exists($fname)) {
			@unlink($fname);
		}
	}
}
