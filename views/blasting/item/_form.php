<?php
/**
 * Event Blasting Items (event-blasting-item)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\ItemController
 * @var $model ommu\event\models\EventBlastingItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 December 2017, 15:04 WIB
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
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'blast_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('blast_id')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'views')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('views')); ?>

<?php echo $form->field($model, 'view_ip')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('view_ip')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>