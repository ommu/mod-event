<?php
/**
 * Event Settings (event-setting)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\setting\AdminController
 * @var $model ommu\event\models\EventSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 09:41 WIB
 * @modified date 23 June 2019, 20:09 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="event-setting-form">

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

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'event_price')
	->textInput(['type'=>'number', 'min'=>'0'])
	->label($model->getAttributeLabel('event_price')); ?>

<?php echo $form->field($model, 'event_agreement')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('event_agreement')); ?>

<?php echo $form->field($model, 'event_warning_message')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('event_warning_message')); ?>

<?php $eventNotifyDiffType = $model::getEventNotifyDiffType();
$event_notify_diff_type = $form->field($model, 'event_notify_diff_type', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->dropDownList($eventNotifyDiffType, ['prompt'=>''])
	->label($model->getAttributeLabel('event_notify_diff_type')); ?>

<?php echo $form->field($model, 'event_notify_difference', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$event_notify_diff_type.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('event_notify_difference')); ?>

<?php $event_banned_diff_type = $form->field($model, 'event_banned_diff_type', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->dropDownList($eventNotifyDiffType, ['prompt'=>''])
	->label($model->getAttributeLabel('event_banned_diff_type')); ?>

<?php echo $form->field($model, 'event_banned_difference', ['template' => '{label}{beginWrapper}{input}{endWrapper}'.$event_banned_diff_type.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-sm-4 col-xs-6', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('event_banned_difference')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>