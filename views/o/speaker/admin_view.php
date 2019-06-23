<?php
/**
 * Event Advisers (event-adviser)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\SpeakerController
 * @var $model ommu\event\models\EventSpeaker
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 11:43 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Advisers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="event-xxx-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'eventTitle',
		'value' => $model->batch->event->title,
	],
	[
		'attribute' => 'batchName',
		'value' => $model->batch->batch_time.' - '.$model->batch->batch_name,
	],
	[
		'attribute' => 'userDisplayname',
		'value' => $model->user_id ? $model->user->displayname : '-',
	],
	'speaker_name',
	'speaker_position',
	[
		'attribute' => 'creation_date',
		'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => $model->creation_id ? $model->creation->displayname : '-',
	],
	[
		'attribute' => 'modified_date',
		'value' => !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'datetime') : '-',
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => $model->modified_id ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'datetime') : '-',
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