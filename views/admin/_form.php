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
 * @modified date 24 June 2019, 10:28 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;
use ommu\event\models\Events;
use ommu\event\models\EventCategory;
use ommu\selectize\Selectize;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="events-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = EventCategory::getCategory();
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php echo $form->field($model, 'title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('title')); ?>

<?php echo $form->field($model, 'theme')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('theme')); ?>

<?php echo $form->field($model, 'introduction')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => [
		'buttons' => ['html', 'format', 'bold', 'italic', 'underline', 'deleted', 'link'],
		'plugins' => ['fontcolor']
	]])
	->label($model->getAttributeLabel('introduction')); ?>

<?php echo $form->field($model, 'description')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('description')); ?>

<?php $uploadPath = join('/', [Events::getUploadPath(false), $model->event_id]);
$coverFilename = !$model->isNewRecord && $model->old_cover_filename != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_cover_filename])), ['class'=>'mb-3']) : '';
echo $form->field($model, 'cover_filename', ['template' => '{label}{beginWrapper}<div>'.$coverFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('cover_filename')); ?>

<?php $uploadPath = join('/', [Events::getUploadPath(false), $model->event_id]);
$bannerFilename = !$model->isNewRecord && $model->old_banner_filename != '' ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_banner_filename])), ['class'=>'mb-3']) : '';
echo $form->field($model, 'banner_filename', ['template' => '{label}{beginWrapper}<div>'.$bannerFilename.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('banner_filename')); ?>

<?php $tagSuggestUrl = Url::to(['/admin/tag/suggest']);
echo $form->field($model, 'tag')
	->widget(Selectize::className(), [
		'url' => $tagSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('tag')); ?>

<div class="ln_solid"></div>

<?php $registeredEnable = Events::getRegisteredEnable();
echo $form->field($model, 'registered_enable')
	->dropDownList($registeredEnable, ['prompt' => ''])
	->label($model->getAttributeLabel('registered_enable')); ?>

<?php $registeredType = Events::getRegisteredType();
echo $form->field($model, 'registered_type')
	->dropDownList($registeredType, ['prompt' => ''])
	->label($model->getAttributeLabel('registered_type')); ?>

<?php echo $form->field($model, 'registered_message')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => [
		'buttons' => ['html', 'format', 'bold', 'italic', 'underline', 'deleted', 'indent', 'outdent', 'link'],
		'plugins' => ['fontcolor']
	]])
	->label($model->getAttributeLabel('registered_message')); ?>

<div class="ln_solid"></div>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->published_date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
echo $form->field($model, 'published_date')
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('published_date')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>