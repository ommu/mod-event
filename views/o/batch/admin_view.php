<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 09:40 WIB
 * @modified date 26 June 2019, 14:39 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\grid\GridView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->batch_name;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Back to Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="event-batch-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['o/batch/publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'batch_name',
		'value' => $model->batch_name ? $model->batch_name : '-',
	],
	[
		'attribute' => 'eventTitle',
		'value' => function ($model) {
			$eventTitle = isset($model->event) ? $model->event->title : '-';
            if ($eventTitle != '-') {
                return Html::a($eventTitle, ['admin/view', 'id' => $model->event_id], ['title' => $eventTitle, 'class' => 'modal-btn']);
            }
			return $eventTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'eventCategoryId',
		'value' => function ($model) {
			$eventCategoryName = isset($model->event->category) ? $model->event->category->title->message : '-';
            if ($eventCategoryName != '-') {
                return Html::a($eventCategoryName, ['setting/category/view', 'id' => $model->event->cat_id], ['title' => $eventCategoryName, 'class' => 'modal-btn']);
            }
			return $eventCategoryName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'batch_desc',
		'value' => $model->batch_desc ? $model->batch_desc : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'batch_location',
		'value' => $model->batch_location ? $model->batch_location : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'location_name',
		'value' => $model->location_name ? $model->location_name : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'location_address',
		'value' => $model->location_address ? $model->location_address : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'batch_date',
		'value' => Yii::$app->formatter->asDate($model->batch_date, 'medium'),
	],
	[
		'attribute' => 'batch_time',
		'value' => $model::parseBatchTime($model->batch_time),
	],
	[
		'attribute' => 'speakers',
		'value' => GridView::widget([
			'dataProvider' => $model->getSpeakers('dataProvider'),
			'columns' => [
				[
					'attribute' => 'speaker_name',
					'value' => function($model, $key, $index, $column) {
						return $model->speaker_name;
					},
					'enableSorting' => false,
				],
				[
					'attribute' => 'speaker_position',
					'value' => function($model, $key, $index, $column) {
						return $model->speaker_position;
					},
					'enableSorting' => false,
				],
				[
					'attribute' => 'session_title',
					'value' => function($model, $key, $index, $column) {
						return $model->session_title;
					},
					'enableSorting' => false,
				],
			],
			'layout' => '{items}'.Html::a(Yii::t('app', 'add speaker'), ['o/speaker/create', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'add speaker'), 'class' => 'modal-btn']).' | '.Html::a(Yii::t('app', 'show all speaker'), ['o/speaker/manage', 'id' => $model->primaryKey], ['title' => Yii::t('app', '{count} speakers', ['count' => $model->getSpeakers('count')])]),
		]),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'batch_price',
		'value' => $model->batch_price ? $model->batch_price : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'registered_limit',
		'value' => $model->registered_limit ? $model->registered_limit : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'registereds',
		'value' => function ($model) {
			$registereds = $model->getRegistereds(true);
			return Html::a($registereds, ['registered/batch/manage', 'batch' => $model->primaryKey], ['title' => Yii::t('app', '{count} registereds', ['count' => $registereds])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
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
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>