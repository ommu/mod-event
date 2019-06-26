<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:40 WIB
 * @modified date 26 June 2019, 14:39 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\event\models\EventBatch;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->batch_name;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="event-batch-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
	[
		'attribute' => 'eventTitle',
		'value' => function ($model) {
			$eventTitle = isset($model->event) ? $model->event->title : '-';
			if($eventTitle != '-')
				return Html::a($eventTitle, ['admin/view', 'id'=>$model->event_id], ['title'=>$eventTitle, 'class'=>'modal-btn']);
			return $eventTitle;
		},
		'format' => 'html',
	],
	'batch_name',
	[
		'attribute' => 'batch_desc',
		'value' => $model->batch_desc ? $model->batch_desc : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'batch_date',
		'value' => Yii::$app->formatter->asDate($model->batch_date, 'medium'),
	],
	[
		'attribute' => 'batch_time',
		'value' => serialize($model->batch_time),
	],
	[
		'attribute' => 'speakers',
		'value' => function ($model) {
			$speakers = $model->getSpeakers(true);
			return Html::a($speakers, ['o/speaker/manage', 'batch'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} speakers', ['count'=>$speakers])]);
		},
		'format' => 'html',
	],
	'batch_price',
	'batch_location',
	'location_name',
	'location_address',
	'registered_limit',
	[
		'attribute' => 'registereds',
		'value' => function ($model) {
			$registereds = $model->getRegistereds(true);
			return Html::a($registereds, ['registered/batch/manage', 'batch'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} registereds', ['count'=>$registereds])]);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
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
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
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