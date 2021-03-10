<?php
/**
 * Event Speakers (event-speaker)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 11:43 WIB
 * @modified date 26 June 2019, 22:56 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use ommu\selectize\Selectize;

$js = <<<JS
	var options = '';
	var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
		'(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
?>

<div class="event-speaker-form">

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

<?php 
$userSuggestUrl = Url::to(['/users/member/suggest']);
$pluginOptions = [
	'valueField' => 'id',
	'labelField' => 'name',
	'searchField' => ['name', 'email'],
	'maxItems' => '1',
	'persist' => false,
	'render' => [
		'item' => new JsExpression('function(item, escape) {
			return \'<div>\' +
				(item.name ? \'<span class="name">\' + escape(item.name) + \'</span>\' : \'\') +
				(item.email ? \'<span class="email">\' + escape(item.email) + \'</span>\' : \'\') +
			\'</div>\';
		}'),
		'option' => new JsExpression('function(item, escape) {
			var label = item.name || item.email;
			var caption = item.name ? item.email : null;
			return \'<div>\' +
				\'<span class="label">\' + escape(label) + \'</span>\' +
				(caption ? \'<span class="caption">\' + escape(caption) + \'</span>\' : \'\') +
			\'</div>\';
		}'),
	],
	'createFilter' => new JsExpression('function(input) {
		var match, regex;

		regex = new RegExp(\'^\' + REGEX_EMAIL + \'$\', \'i\');
		match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[0]);

		regex = new RegExp(\'^([^<]*)\<\' + REGEX_EMAIL + \'\>$\', \'i\');
		match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[2]);

		return false;
	}'),
	'create' => new JsExpression('function(input) {
        if ((new RegExp(\'^\' + REGEX_EMAIL + \'$\', \'i\')).test(input)) {
			return {email: input};
		}
		var match = input.match(new RegExp(\'^([^<]*)\<\' + REGEX_EMAIL + \'\>$\', \'i\'));
        if (match) {
			return {
				email : match[2],
				name  : $.trim(match[1])
			};
		}
		alert(\'Invalid email address.\');
		return false;
	}'),
	'onChange' => new JsExpression('function(value) {
		options = this.options;
		var userSelected = this.options[value];
		$(\'form\').find(\'#speaker_name\').val(userSelected.name);
	}'),
	'onDelete' => new JsExpression('function(value) {
		user_id.clear();
		user_id.clearOptions();
	}'),
];
if ($model->user_id && isset($model->user)) {
	$pluginOptions = ArrayHelper::merge($pluginOptions, [
		'options' => [[
			'id' => $model->user->user_id,
			'email' => $model->user->email,
			'name' => $model->user->displayname,
			'photo' => $model->user->photos,
		]]
	]);
}
echo $form->field($model, 'user_id')
	->widget(Selectize::className(), [
		'cascade' => true,
		'options' => [
			'placeholder' => Yii::t('app', 'Pick some people...'),
			'class' => 'form-control contacts',
		],
		'url' => $userSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => $pluginOptions,
	])
	->label($model->getAttributeLabel('user_id')); ?>

<?php echo $form->field($model, 'speaker_name')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('speaker_name')); ?>

<?php echo $form->field($model, 'speaker_position')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('speaker_position')); ?>

<?php echo $form->field($model, 'session_title')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('session_title')); ?>

<?php 
if (!$model->isNewRecord) {
    echo $form->field($model, 'publish')
        ->checkbox()
        ->label($model->getAttributeLabel('publish'));
} ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>