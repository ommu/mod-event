<?php
/**
 * Event Advisers (event-adviser)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 11:43 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use ommu\event\models\EventBatch;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
if(($batch = Yii::$app->request->get('batch')) != null)
	$model->batch_id = $batch;
	echo $form->field($model, 'batch_id')->hiddenInput()
	->label(false);
?>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="consultationonlines-subject_id"><?php echo Yii::t('app', 'Batch');?></label>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<?php 
			echo Yii::t('app', 'Event: {event_title}', ['event_title'=>$batch->event->title])."<br />";
			echo Yii::t('app', 'Batch: {batch_name}', ['batch_name'=>$batch->batch_name])."<br />";
?>
		</div>
	</div>
<?php
} else {
$batch_id = EventBatch::getBatch(1);
echo $form->field($model, 'batch_id')
		->dropDownList($batch_id, ['prompt' => ' Event Title - Batch Title'])
		->label($model->getAttributeLabel('batch_id'));
}
?>

<?php echo $form->field($model, 'user_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'speaker_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('speaker_name')); ?>

<?php echo $form->field($model, 'speaker_position')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('speaker_position')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>