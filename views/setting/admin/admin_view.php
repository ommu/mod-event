<?php
/**
 * Event Settings (event-setting)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\setting\AdminController
 * @var $model ommu\event\models\EventSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 23 June 2019, 20:09 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\event\models\EventSetting;

$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
?>

<div class="event-setting-view">

<?php
$attributes = [
	'id',
	'license',
	[
		'attribute' => 'permission',
		'value' => EventSetting::getPermission($model->permission),
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
		'value' => $model->event_notify_difference.' '.EventSetting::getEventNotifyDiffType($model->event_notify_diff_type),
	],
	[
		'attribute' => 'event_banned_difference',
		'value' => $model->event_banned_difference.' '.EventSetting::getEventNotifyDiffType($model->event_banned_diff_type),
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
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