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
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;
	
if (($batch = Yii::$app->request->get('batch')) != null) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Back To Manage Batch'), 'url' => Url::to(['batch/index']), 'icon' => 'table'],
		['label' => Yii::t('app', 'Add Event Adviser'), 'url' => Url::to(['create', 'batch' => $batch]), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
	];
} else {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Event Adviser'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
	];
}	

$this->params['menu']['option'] = [
	// ['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Options'), 'url' => 'javascript:void(0);'],
];
?>

<div class="event-xxx-manage">
<?php Pjax::begin(); ?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['delete', 'id'=>$key]);
	},
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>