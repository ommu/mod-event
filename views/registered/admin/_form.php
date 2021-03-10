<?php
/**
 * Event Registereds (event-registered)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\EventRegistered
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\event\models\EventRegistered;
use yii\helpers\ArrayHelper;
use ommu\selectize\Selectize;
use yii\web\JsExpression;

$js = <<<JS
	var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
		'(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
JS;
	$this->registerJs($js, \yii\web\View::POS_END);
?>

<div class="event-registered-form">

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
		'options' => [
			'placeholder' => Yii::t('app', 'Pick some people...'),
			'class' => 'form-control contacts',
			'disabled' => !$model->isNewRecord ? true : false,
		],
		'url' => $userSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => $pluginOptions,
	])
	->label($model->getAttributeLabel('user_id')); ?>

<?php 
if (!$model->event->isPackage) {
    $batchSelectizeOptions = [
        'options' => [
            'placeholder' => Yii::t('app', 'Select a batch...'),
        ],
        'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a batch..')], $model->event->getBatches('array', 'title')),
    ];
    if ($model->event->registered_type == 'multiple') {
        $batchSelectizeOptions = ArrayHelper::merge($batchSelectizeOptions, [
            'options' => [
                'multiple' => true,
                'disabled' => !$model->isNewRecord ? true : false,
            ],
            'pluginOptions' => [
                'plugins' => ['remove_button'],
            ],
        ]);
    }
    echo $form->field($model, 'batch')
        ->widget(Selectize::className(), $batchSelectizeOptions)
        ->label($model->getAttributeLabel('batch'));
}?>

<?php 
if (!$model->isNewRecord) {
echo $form->field($finance, 'price')
	->textInput(['type' => 'number', 'min' => 0, 'disabled' => !$model->isNewRecord ? true : false,])
	->label($finance->getAttributeLabel('price')); ?>

<?php echo $form->field($finance, 'payment')
	->textInput(['type' => 'number', 'min' => 0, 'disabled' => !$model->isNewRecord ? true : false,])
	->label($finance->getAttributeLabel('payment')); ?>

<?php echo $form->field($finance, 'reward')
	->textInput(['type' => 'number', 'min' => 0, 'disabled' => !$model->isNewRecord ? true : false,])
	->label($finance->getAttributeLabel('reward')); ?>

<hr/>

<?php $status = EventRegistered::getStatus();
echo $form->field($model, 'status')
	->dropDownList($status, ['prompt' => ''])
	->label($model->getAttributeLabel('status'));
} ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>