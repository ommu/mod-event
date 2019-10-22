<?php
/**
 * Event Filter Universities (event-filter-university)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\filter\UniversityController
 * @var $model ommu\event\models\EventFilterUniversity
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:25 WIB
 * @modified date 24 June 2019, 20:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Filter Universities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->event->title;
?>

<div class="event-filter-university-view">

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
		'attribute' => 'universityName',
		'value' => function ($model) {
			$universityName = isset($model->university) ? $model->university->company->company_name : '-';
			if($universityName != '-')
				return Html::a($universityName, ['university/view', 'id'=>$model->university_id], ['title'=>$universityName, 'class'=>'modal-btn']);
			return $universityName;
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