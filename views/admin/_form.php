<?php
/**
 * Events (events)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\redactor\widgets\Redactor;
use ommu\event\models\EventCategory;
use ommu\event\models\Events;
use ommu\event\models\EventTag;
use ommu\event\models\EventFilterGender;
use ommu\event\models\EventFilterMajor;
use ommu\event\models\EventFilterUniversity;
use ommu\event\models\EventSetting;
use app\modules\ipedia\models\IpediaDirectoryLocation;
use app\modules\ipedia\models\IpediaMajor;
use app\modules\ipedia\models\IpediaUniversity;
use app\models\CoreTags;
use yii\helpers\ArrayHelper;
use yii2mod\selectize\Selectize;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor','imagemanager']
];

$js = <<<JS
	var type = $('#registered_enable').val();
	var id = $('#registered_enable').prop('checked');
	if(id == true) {
	$('div.field-registered_message').hide();
	$('div.field-registered_type').show();
	} else {
	$('div.field-registered_message').show();
	$('div.field-registered_type').hide();
	}

	$('#registered_enable').on('change', function() {
		var id = $(this).prop('checked');
		if(id == true) {
			$('div.field-registered_type').slideDown();
			$('div.field-registered_message').slideUp();
		} else {
			$('div.field-registered_type').slideUp();
			$('div.field-registered_message').slideDown();
		}
	});

	var type2 = $('#enable_filter').val();
	var id2 = $('#enable_filter').prop('checked');
	if(id2 == true) {
		
	$('#filter').show();
	} else {
	$('#filter').hide();
	}

	$('#enable_filter').on('change', function() {
		var id2 = $(this).prop('checked');
		if(id2 == true) {
			$('#filter').slideDown();
		} else {
			$('#filter').slideUp();
		}
	});
JS;
$this->registerJs($js, \app\components\View::POS_READY);
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
$event_category = EventCategory::getCategory(1);
echo $form->field($model, 'cat_id')
		->dropDownList($event_category, ['prompt' => ''])
		->label($model->getAttributeLabel('cat_id'));
?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'theme')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('theme')); ?>

<?php echo $form->field($model, 'introduction')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('introduction')); ?>

<?php echo $form->field($model, 'description')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('description')); ?>

<!-- Upload Cover -->
<div class="form-group field-events-cover_filename required">
	<?php echo $form->field($model, 'cover_filename', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('cover_filename')); ?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<!-- Cover Lama -->
		<?php if (!$model->isNewRecord) {
			if($model->old_cover != '')
				echo Html::img(join('/', [Url::Base(), Events::getCoverPath(false), $model->old_cover]), ['class'=>'mb-15', 'width'=>'100%']);
		} ?>

		<?php echo $form->field($model, 'cover_filename', ['template' => '{input}{error}'])
			->fileInput() 
			->label($model->getAttributeLabel('cover_filename')); ?>
	</div>
</div>

<!-- Upload Banner -->
<div class="form-group field-events-banner_filename required">
	<?php echo $form->field($model, 'banner_filename', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('banner_filename')); ?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<!-- Cover Lama -->
		<?php if (!$model->isNewRecord) {
			if($model->old_banner != '')
				echo Html::img(join('/', [Url::Base(), Events::getBannerPath(false), $model->old_banner]), ['class'=>'mb-15', 'width'=>'100%']);
		} ?>
		<?php echo $form->field($model, 'banner_filename', ['template' => '{input}{error}'])
			->fileInput() 
			->label($model->getAttributeLabel('banner_filename')); ?>
	</div>
</div>

<?php echo $form->field($model, 'registered_enable')
	->checkbox()
	->label($model->getAttributeLabel('registered_enable')); ?>

<!-- <div id="registered"> -->
<?php echo $form->field($model, 'registered_message')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('registered_message')); ?>

<?php echo $form->field($model, 'registered_type')
	->dropDownList([ 'single' => 'Single', 'multiple' => 'Multiple', 'package' => 'Package', ], ['prompt' => ''])
	->label($model->getAttributeLabel('registered_type')); ?>
<!-- </div> -->

<!-- Tags -->
<?php 
if (!$model->isNewRecord) {
	$update_tag = EventTag::find()->where(['event_id' => $model->event_id, 'publish' => 1])->all();
	if ($update_tag != null){
		$arrayTag = [];
		$arrayTagId = [];
		$model->tag_id_i = [];
		foreach ($update_tag as $value) {
			$arrayTag[] = $value->tag->body;
			$arrayTagId[] = $value->tag_id;
		}
		$model->tag_hidden = implode(',', $arrayTag);
		$model->tag_id_i = $arrayTagId;
	}
}

echo $form->field($model, 'tag_hidden')->hiddenInput()
	->label(false);

echo $form->field($model, 'tag_id_i',['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
	->widget(Selectize::className(), [
		'items' => ArrayHelper::map(CoreTags::find()->all(), 'tag_id', 'body'),
		'options' => [
			'multiple' => true,
		],
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => true,
			'create' => true,
			'onItemAdd' => new \yii\web\JsExpression('function(value, $item) { 
				var tag = $item["context"].innerHTML; 
				tags.push(tag); 
				$("#events-tag_hidden").val(tags.join(",")); 
			}'),
			'onItemRemove' => new \yii\web\JsExpression('function(value, $item) { 
				var tag = $item["context"].innerHTML; 
				var pos = tags.indexOf(tag); 
				if(pos > -1) { 
					tags.splice(pos, 1)
				}; 
				$("#events-tag_hidden").val(tags.join(",")); 
			}'),
		]
	])
 	->label($model->getAttributeLabel('tag_id_i'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12', 'id'=>'inioy']); 
?>

<?php echo $form->field($model, 'enable_filter')
	->checkbox()
	->label($model->getAttributeLabel('enable_filter')); ?>

<div id="filter">
	<!-- Gender -->
	<?php 
	$update_gender = EventFilterGender::find()->where(['event_id' => $model->event_id])->one();
	if ((!$model->isNewRecord && $model->enable_filter == 1) || $update_gender != null) {
	// if (!$model->isNewRecord && $model->enable_filter == 1) {
		// $update_gender = EventFilterGender::find()->where(['event_id' => $model->event_id])->one();
		if (isset($update_gender))
			$model->filter_gender = $update_gender->gender;	
	}
	echo $form->field($model, 'filter_gender')
	->dropDownList([ 'male' => 'Male', 'female' => 'Female', ], ['prompt' => ''])
	->label($model->getAttributeLabel('filter_gender')); 
	?>
	
	<!-- Major -->
	<?php 
	$update_major = EventFilterMajor::find()->where(['event_id' => $model->event_id])->all();
	if ((!$model->isNewRecord && $model->enable_filter == 1) || $update_major != null) {
	// if (!$model->isNewRecord && $model->enable_filter == 1) {
		// $update_major = EventFilterMajor::find()->where(['event_id' => $model->event_id])->all();
		$arrayMajor = [];
		$arrayMajorId = [];
		$model->filter_major = [];
		foreach ($update_major as $value) {
			$arrayMajor[] = $value->major->another->another_name;
			$arrayMajorId[] = $value->major_id;
		}
		$model->major_hidden = implode(',', $arrayMajor);
		$model->filter_major = $arrayMajorId;
	}

	echo $form->field($model, 'major_hidden')->hiddenInput()
	->label(false);

	echo $form->field($model, 'filter_major',['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
		->widget(Selectize::className(), [
			'items' => IpediaMajor::getMajor(1),
			'options' => [
				'multiple' => true,
			],
			'pluginOptions' => [
				'persist' => false,
				'createOnBlur' => true,
				'create' => true,
				'onItemAdd' => new \yii\web\JsExpression('function(value, $item) { 
					var major = $item[0].innerHTML; 
					majors.push(major); 
					$("#major_hidden").val(majors.join(",")); 
				}'),
				'onItemRemove' => new \yii\web\JsExpression('function(value, $item) { 
				console.log($item[0].innerHTML);
					var major = $item[0].innerHTML; 
					var pos = majors.indexOf(major); 
					if(pos > -1) { 
						majors.splice(pos, 1)
					}; 
					$("#major_hidden").val(majors.join(",")); 
				}'),
			]
		])
 		->label($model->getAttributeLabel('filter_major')); 
	?>

	<!-- University -->
	<?php 
	$update_university = EventFilterUniversity::find()->where(['event_id' => $model->event_id])->all();
	if ((!$model->isNewRecord && $model->enable_filter == 1) || $update_university != null) {
	// if (!$model->isNewRecord && $model->enable_filter == 1) {
		// $update_university = EventFilterUniversity::find()->where(['event_id' => $model->event_id])->all();
		$arrayUniversity = [];
		$model->filter_university = [];
		foreach ($update_university as $value) {
			$arrayUniversity[] = $value->university_id;
		}
		$model->university_hidden = implode(',', $arrayUniversity);
		$model->filter_university = $arrayUniversity;
	}

	echo $form->field($model, 'university_hidden')->hiddenInput()
	->label(false);

	echo $form->field($model, 'filter_university',['template' => '{label}<div class="col-md-9 col-sm-9 col-xs-12">{input}{error}</div>'])
		->widget(Selectize::className(), [
			'items' => IpediaUniversity::getUniversity(1),
			'options' => [
				'multiple' => true,
			],
			'pluginOptions' => [
				'create' => false,
				'onItemAdd' => new \yii\web\JsExpression('function(value, $item) { 
					var university = value; 
					universities.push(university); 
					$("#university_hidden").val(universities.join(",")); 
				}'),
				'onItemRemove' => new \yii\web\JsExpression('function(value, $item) { 
					var university = value; 
					var pos = universities.indexOf(university); 
					if(pos > -1) { 
						universities.splice(pos, 1)
					}; 
					$("#university_hidden").val(universities.join(",")); 
				}'),
			]
		])
 		->label($model->getAttributeLabel('filter_university')); 
	?>
</div>

<?php echo $form->field($model, 'published_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('published_date')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

<!-- Tags, Majors, Universities -->
<?php
// set selected jika update
if (Yii::$app->request->get('event')) {

	if ($update_tag != null) {
		$event = 1;
		$comma_separated_tag = implode("','", $arrayTag);
		$tag_update = "'".$comma_separated_tag."'";
	} else {
		$event = 2;
		$tag_update = '';
	}

	if ($update_major != null) {
		$comma_separated_major = implode("','", $arrayMajor);
		$major_update = "'".$comma_separated_major."'";
	} else {
		$major_update = '';
	}

	if ($update_university != null) {
		$comma_separated_university = implode("','", $arrayUniversity);
		$universities_update = "'".$comma_separated_university."'";
	} else {
		$universities_update  = '';
	}

} else {
	$event = 2;

	$tag_update = '';
	$major_update = '';
	$universities_update  = '';
}

$jstag = <<<JS
event = $event;
if (event === 2) {
	var tags = [];
} else if (event === 1){
	var tags = [$tag_update];
} 

var majors = [$major_update];
var universities = [$universities_update];

JS;
$this->registerJs($jstag, \yii\web\View::POS_HEAD);