<?php
/**
 * Event Settings (event-setting)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\setting\AdminController
 * @var $model ommu\event\models\EventSetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 23 June 2019, 20:09 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = $this->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="event-setting-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'license',
		'value' => $model->license,
		'visible' => !$small,
	],
	[
		'attribute' => 'permission',
		'value' => $model::getPermission($model->permission),
	],
	[
		'attribute' => 'meta_keyword',
		'value' => $model->meta_keyword ? $model->meta_keyword : '-',
	],
	[
		'attribute' => 'meta_description',
		'value' => $model->meta_description ? $model->meta_description : '-',
	],
	'event_price',
	[
		'attribute' => 'event_agreement',
		'value' => $model->event_agreement ? $model->event_agreement : '-',
	],
	[
		'attribute' => 'event_warning_message',
		'value' => serialize($model->event_warning_message),
	],
	[
		'attribute' => 'event_notify_difference',
		'value' => $model->event_notify_difference.' '.$model::getEventNotifyDiffType($model->event_notify_diff_type),
	],
	[
		'attribute' => 'event_banned_difference',
		'value' => $model->event_banned_difference.' '.$model::getEventNotifyDiffType($model->event_banned_diff_type),
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update'], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>