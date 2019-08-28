<?php
/**
 * Event Registereds (event-registered)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\EventRegistered
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\event\models\EventRegistered;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Registereds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->event->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="event-registered-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
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
	[
		'attribute' => 'eventCategoryId',
		'value' => function ($model) {
			$eventCategoryName = isset($model->event->category) ? $model->event->category->title->message : '-';
			if($eventCategoryName != '-')
				return Html::a($eventCategoryName, ['setting/category/view', 'id'=>$model->event->cat_id], ['title'=>$eventCategoryName, 'class'=>'modal-btn']);
			return $eventCategoryName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'batch',
		'value' => function ($model) {
			$batches = $model->getBatches('array', 'title');
			return Html::ul($batches, ['encode'=>false, 'class'=>'list-boxed']);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'finance.price',
		'value' => isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->price) : '-',
	],
	[
		'attribute' => 'finance.reward',
		'value' => isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->reward) : '-',
	],
	[
		'attribute' => 'finance.payment',
		'value' => isset($model->finance) ? Yii::$app->formatter->asCurrency($model->finance->payment) : '-',
	],
	[
		'attribute' => 'status',
		'value' => EventRegistered::getStatus($model->status),
		'visible' => !$small,
	],
	[
		'attribute' => 'confirmation_date',
		'value' => Yii::$app->formatter->asDatetime($model->confirmation_date, 'medium'),
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
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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