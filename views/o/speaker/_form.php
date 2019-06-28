<?php
/**
 * Event Speakers (event-speaker)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 11:43 WIB
 * @modified date 26 June 2019, 22:56 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="event-speaker-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type'=>'number'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'speaker_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('speaker_name')); ?>

<?php echo $form->field($model, 'speaker_position')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('speaker_position')); ?>

<?php echo $form->field($model, 'session_title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('session_title')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>