<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:40 WIB
 * @modified date 26 June 2019, 14:39 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\event\models\EventBatch;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="event-batch-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'batch_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('batch_name')); ?>

<?php echo $form->field($model, 'batch_desc')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('batch_desc')); ?>

<hr/>

<?php echo $form->field($model, 'batch_location')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('batch_location')); ?>

<?php echo $form->field($model, 'location_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('location_name')); ?>

<?php echo $form->field($model, 'location_address')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('location_address')); ?>

<?php echo $form->field($model, 'batch_date')
	->textInput(['type'=>'date'])
	->label($model->getAttributeLabel('batch_date')); ?>

<?php $batch_time_start = $form->field($model, 'batch_time[start]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'time'])
	->label($model->getAttributeLabel('batch_time[start]')); ?>

<?php echo $form->field($model, 'batch_time[end]', ['template' => '{label}'.$batch_time_start.'{beginWrapper}{input}{endWrapper}{error}{hint}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6', 'error'=>'col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'time'])
	->label($model->getAttributeLabel('batch_time')); ?>

<hr/>

<?php echo $form->field($model, 'batch_price')
	->textInput(['type'=>'number', 'min'=>'0'])
	->label($model->getAttributeLabel('batch_price')); ?>

<?php echo $form->field($model, 'registered_limit')
	->textInput(['type'=>'number', 'min'=>'0'])
	->label($model->getAttributeLabel('registered_limit')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>