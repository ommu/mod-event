<?php
/**
 * Event Filter Majors (event-filter-major)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\filter\MajorController
 * @var $model ommu\event\models\EventFilterMajor
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
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Filter Majors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->event->title;
?>

<div class="event-filter-major-view">

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
            if ($eventTitle != '-') {
                return Html::a($eventTitle, ['admin/view', 'id'=>$model->event_id], ['title'=>$eventTitle, 'class'=>'modal-btn']);
            }
			return $eventTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'majorName',
		'value' => function ($model) {
			$majorName = isset($model->major) ? $model->major->major_name : '-';
            if ($majorName != '-') {
                return Html::a($majorName, ['major/view', 'id'=>$model->major_id], ['title'=>$majorName, 'class'=>'modal-btn']);
            }
			return $majorName;
		},
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