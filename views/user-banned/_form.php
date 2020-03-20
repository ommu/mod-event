<?php
/**
 * Event User Banneds (event-user-banned)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\UserBannedController
 * @var $model ommu\event\models\EventUserBanned
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
echo $form->field($model, 'status')
	->checkbox()
	->label($model->getAttributeLabel('status')); 
?>

<?php echo $form->field($model, 'event_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('event_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'banned_desc')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('banned_desc')); ?>

<?php 
echo $form->field($model, 'unbanned_agreement')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('unbanned_agreement')); 
?>

<?php 
echo $form->field($model, 'unbanned_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('unbanned_id')); 
?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>