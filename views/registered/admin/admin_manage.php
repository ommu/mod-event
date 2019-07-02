<?php
/**
 * Event Registereds (event-registered)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\EventRegistered
 * @var $searchModel ommu\event\models\search\EventRegistered
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = $this->title;

if(($id = Yii::$app->request->get('id')) != null) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Registered'), 'url' => Url::to(['create', 'id'=>$id]), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn modal-btn btn-success']],
	];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="event-registered-manage">
<?php Pjax::begin(); ?>

<?php if($event != null)
	echo $this->render('/admin/admin_view', ['model'=>$event, 'small'=>true]); ?>

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