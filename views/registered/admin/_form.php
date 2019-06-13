<?php
/**
 * Event Registereds (event-registereds)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\EventRegistered
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:43 WIB
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

<?php echo $form->field($model, 'status')
	->checkbox()
	->label($model->getAttributeLabel('status')); ?>

<?php echo $form->field($model, 'event_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('event_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('user_id')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>