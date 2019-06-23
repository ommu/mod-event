<?php
/**
 * Event Blasting Items (event-blasting-item)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\BlastingItemController
 * @var $model ommu\event\models\EventBlastingItem
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Blasting Items'), 'url' => ['index']];
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
		'attribute' => 'blasting_search',
		'value' => $model->blasting->blast_id,
	],
	[
		'attribute' => 'user_search',
		'value' => $model->user_id ? $model->user->displayname : '-',
	],
	'views',
	[
		'attribute' => 'view_date',
		'value' => !in_array($model->view_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->view_date, 'datetime') : '-',
	],
	'view_ip',
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
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>