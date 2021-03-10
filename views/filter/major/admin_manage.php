<?php
/**
 * Event Filter Majors (event-filter-major)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\filter\MajorController
 * @var $model ommu\event\models\EventFilterMajor
 * @var $searchModel ommu\event\models\search\EventFilterMajor
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 09:22 WIB
 * @modified date 24 June 2019, 20:13 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\event\models\Events;
use ommu\ipedia\models\IpediaMajors;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="event-filter-major-manage">
<?php Pjax::begin(); ?>

<?php if ($event != null) {
$model = $event;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'categoryName',
			'value' => function ($model) {
				$categoryName = isset($model->category) ? $model->category->title->message : '-';
                if ($categoryName != '-') {
                    return Html::a($categoryName, ['setting/category/view', 'id' => $model->cat_id], ['title' => $categoryName, 'class' => 'modal-btn']);
                }
				return $categoryName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'title',
			'value' => function ($model) {
                if ($model->title != '') {
                    return Html::a($model->title, ['admin/view', 'id' => $model->id], ['title' => $model->title, 'class' => 'modal-btn']);
                }
				return $model->title;
			},
			'format' => 'html',
		],
		'theme',
		[
			'attribute' => 'introduction',
			'value' => $model->introduction ? $model->introduction : '-',
		],
	],
]);
}?>

<?php if ($major != null) {
$model = $major;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'major_name',
			'value' => function ($model) {
                if ($model->major_name != '') {
                    return Html::a($model->major_name, ['major/view', 'id' => $model->major_id], ['title' => $model->major_name, 'class' => 'modal-btn']);
                }
				return $model->major_name;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'major_desc',
			'value' => $model->major_desc ? $model->major_desc : '-',
		],
	],
]);
}?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail')]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update')]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>