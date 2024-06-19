<?php
/**
 * Event Blastings (event-blastings)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\AdminController
 * @var $model ommu\event\models\EventBlastings
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Blastings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->blast_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="event-xxx-view">

<?php
$attributes = [
	'blast_id',
	[
		'attribute' => 'eventTitle',
		'value' => $model->event->title,
	],
	[
		'attribute' => 'filter_search',
		'value' => $model->filter->filter_name,
	],
	'users',
	[
		'attribute' => 'blast_with',
		'value' => $model->blast_with ? $model->blast_with : '-',
		'format' => 'html',
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
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>