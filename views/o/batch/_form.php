<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:40 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\redactor\widgets\Redactor;
use ommu\event\models\Events;
use kartik\time\TimePicker;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
// cek jika ada event_id
if(($event = Yii::$app->request->get('event')) != null)
	$model->event_id = $event;
?>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="consultationonlines-subject_id"><?php echo Yii::t('app', 'Event');?></label>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<?php 
			echo Yii::t('app', '{title}', ['title' => $events->title])."<br />";

			echo $form->field($model, 'event_id')->hiddenInput()
				->label(false);
			?>
		</div>
	</div>
<?php
} else {
	// echo $form->field($model, 'event_id')
	// 	->textInput(['maxlength'=>true])
	// 	->label($model->getAttributeLabel('event_id')); 

	$event = Events::getEvent(1);
	echo $form->field($model, 'event_id')
		->dropDownList($event, ['prompt' => ''])
		->label($model->getAttributeLabel('event_id'));
}
?>

<?php echo $form->field($model, 'batch_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('batch_name')); ?>

<?php echo $form->field($model, 'batch_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('batch_date')); ?>

<?php echo $form->field($model, 'batch_time')
	->widget(TimePicker::classname(), [
		'pluginOptions' => [
			'showMeridian' => false,
		]
	])
	->label($model->getAttributeLabel('batch_time')); ?>

<?php echo $form->field($model, 'registered_limit')
	->textInput(['type' => 'number', 'min' => 0])
	->label($model->getAttributeLabel('registered_limit')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>