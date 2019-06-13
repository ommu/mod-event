<?php
/**
 * Event Notifications (event-notification)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\NotificationController
 * @var $model ommu\event\models\EventNotification
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:41 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
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
if (Yii::$app->request->get('id')) {
echo $form->field($model, 'status')
	->checkbox()
	->label($model->getAttributeLabel('status')); 
}
?>

<?php 
if (!Yii::$app->request->get('id')) {
$batch_id = EventBatch::getBatch(1);
echo $form->field($model, 'batch_id')
	// ->textInput(['maxlength'=>true])
	->dropDownList($batch_id, ['prompt' => ' Event Title - Batch Title'])
	->label($model->getAttributeLabel('batch_id')); 
} else {
?>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eventnotification-eventTitle"><?php echo Yii::t('app', 'Event');?></label>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		echo $model->batch->event->title;
		?>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eventnotification-batch_id"><?php echo Yii::t('app', 'Batch');?></label>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		echo Yii::t('app', 'Title: {batch_name}', ['batch_name'=>$model->batch->batch_name])."<br />";
		
		echo Yii::t('app', 'Time: {batch_time}', ['batch_time'=>$model->batch->batch_time])."<br />";
		?>
	</div>
</div>
<?php 
} 
?>

<?php echo $form->field($model, 'notified_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('notified_date')); ?>

<!-- <?php echo $form->field($model, 'notified_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('notified_id')); ?> -->

<!-- <?php echo $form->field($model, 'users')
	->textInput(['type' => 'number', 'min' => 0])
	->label($model->getAttributeLabel('users')); ?> -->

<!-- <?php echo $form->field($model, 'creation_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('creation_date')); ?> -->

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>