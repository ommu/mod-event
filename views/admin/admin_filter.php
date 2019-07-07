<?php
/**
 * Events (events)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @modified date 24 June 2019, 10:28 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\event\models\Events;
use ommu\event\models\EventFilterGender;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Filter');
?>

<div class="events-filter">
<div class="events-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php echo $form->errorSummary($model);?>

<?php $enableFilter = Events::getRegisteredEnable();
echo $form->field($model, 'enable_filter')
	->dropDownList($enableFilter, ['prompt'=>''])
	->label($model->getAttributeLabel('enable_filter')); ?>

<?php echo $form->field($model, 'gender')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a gender..'),
		],
		'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a gender..')], EventFilterGender::getGender()),
	])
	->label($model->getAttributeLabel('gender')); ?>

<?php echo $form->field($model, 'major')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a major..'),
		],
		'url' => 'major-url',
		'queryParam' => 'term',
		'pluginOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
		],
	])
	->label($model->getAttributeLabel('major')); ?>

<?php echo $form->field($model, 'majorGroup')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a major group..'),
		],
		'url' => 'majorGroup-url',
		'queryParam' => 'term',
		'pluginOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
		],
	])
	->label($model->getAttributeLabel('majorGroup')); ?>

<?php echo $form->field($model, 'university')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a university..'),
		],
		'url' => 'university-url',
		'queryParam' => 'term',
		'pluginOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
		],
	])
	->label($model->getAttributeLabel('university')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>
</div>