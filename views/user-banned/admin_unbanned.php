<?php
/**
 * Event User Banneds (event-user-banned)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\UserBannedController
 * @var $model ommu\event\models\EventUserBanned
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event User Banneds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Unbanned');
?>

<div class="event-xxx-view">

<!-- <?php echo $this->render('_form', [
	'model' => $model,
]); ?> -->

<?php
$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	  => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		  => ['clips', 'fontcolor', 'imagemanager']
];

$form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
echo $form->field($model, 'unbanned_agreement')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('unbanned_agreement')); 
?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>