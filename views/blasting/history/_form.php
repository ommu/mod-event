<?php
/**
 * Event Blasting Histories (event-blasting-history)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\HistoryController
 * @var $model ommu\event\models\EventBlastingHistory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\jui\DatePicker;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
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

<?php echo $form->field($model, 'item_id')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('item_id')); ?>

<?php echo $form->field($model, 'view_date')
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['type' => 'date', 'class' => 'form-control']])
	->label($model->getAttributeLabel('view_date')); ?>

<?php echo $form->field($model, 'view_ip')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('view_ip')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>