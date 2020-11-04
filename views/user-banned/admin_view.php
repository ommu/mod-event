<?php
/**
 * Event User Banneds (event-user-banned)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\UserBannedController
 * @var $model ommu\event\models\EventUserBanned
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event User Banneds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (!$small) {
    $this->params['menu']['content'] = [
        // ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->banned_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->banned_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="event-xxx-view">

<?php
$attributes = [
	'banned_id',
	[
		'attribute' => 'status',
		'value' => $model->status == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'eventTitle',
		'value' => $model->event->title,
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'banned_start',
		'value' => !in_array($model->banned_start, ['0000-00-00 00:00:00', '1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->banned_start, 'datetime') : '-',
	],
	[
		'attribute' => 'banned_end',
		'value' => !in_array($model->banned_end, ['0000-00-00 00:00:00', '1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->banned_end, 'datetime') : '-',
	],
	[
		'attribute' => 'banned_desc',
		'value' => $model->banned_desc ? $model->banned_desc : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'unbanned_agreement',
		'value' => $model->unbanned_agreement ? $model->unbanned_agreement : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'unbanned_date',
		'value' => !in_array($model->unbanned_date, ['0000-00-00 00:00:00', '1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->unbanned_date, 'datetime') : '-',
	],
	// 'unbanned_id',
	[
		'attribute' => 'unbanned_search',
		'value' => isset($model->unbanned) ? $model->unbanned->displayname : '-',
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
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
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>