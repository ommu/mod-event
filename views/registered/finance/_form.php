<?php
/**
 * Event Registered Finances (event-registered-finance)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\FinanceController
 * @var $model ommu\event\models\EventRegisteredFinance
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:46 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eventnotification-eventTitle"><?php echo Yii::t('app', 'Event');?></label>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		echo $model->registered->event->title;
		?>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eventnotification-userDisplayname"><?php echo Yii::t('app', 'User');?></label>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		echo $model->registered->user->displayname;
		?>
	</div>
</div>

<?php echo $form->field($model, 'payment')
	->textInput(['type' => 'number', 'min' => 0])
	->label($model->getAttributeLabel('payment')); ?>

<?php echo $form->field($model, 'reward')
	->textInput(['type' => 'number', 'min' => 0])
	->label($model->getAttributeLabel('reward')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>