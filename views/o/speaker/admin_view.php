<?php
/**
 * Event Speakers (event-speaker)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
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
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Speakers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->speaker_name;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="event-speaker-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['o/speaker/publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'speaker_name',
		'value' => $model->speaker_name ? $model->speaker_name : '-',
	],
	[
		'attribute' => 'speaker_position',
		'value' => $model->speaker_position ? $model->speaker_position : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'session_title',
		'value' => $model->session_title ? $model->session_title : '-',
	],
	[
		'attribute' => 'batchName',
		'value' => function ($model) {
			$batchName = isset($model->batch) ? $model->batch->batch_name : '-';
            if ($batchName != '-') {
                return Html::a($batchName, ['o/batch/view', 'id'=>$model->batch_id], ['title'=>$batchName, 'class'=>'modal-btn']);
            }
			return $batchName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'eventTitle',
		'value' => function ($model) {
			$eventTitle = isset($model->batch->event) ? $model->batch->event->title : '-';
            if ($eventTitle != '-') {
                return Html::a($eventTitle, ['admin/view', 'id'=>$model->batch->event_id], ['title'=>$eventTitle, 'class'=>'modal-btn']);
            }
			return $eventTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'eventCategoryId',
		'value' => function ($model) {
			$eventCategoryName = isset($model->batch->event->category) ? $model->batch->event->category->title->message : '-';
            if ($eventCategoryName != '-') {
                return Html::a($eventCategoryName, ['setting/category/view', 'id'=>$model->batch->event->cat_id], ['title'=>$eventCategoryName, 'class'=>'modal-btn']);
            }
			return $eventCategoryName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
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
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-success btn-sm']),
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