<?php
/**
 * Events (events)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\event\models\EventTag;
use ommu\event\models\EventFilterGender;
use ommu\event\models\EventFilterMajor;
use ommu\event\models\EventFilterUniversity;


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'event' => $model->event_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->event_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];

// tag
$update_tag = EventTag::find()->where(['event_id' => $model->event_id, 'publish' => 1])->all();
$arrayTag = [];
$model->tag_id_i = [];
foreach ($update_tag as $value) {
	$arrayTag[] = $value->tag->body;
}
$model->tag_id_i = implode(', ', $arrayTag);

// gender
$update_gender = EventFilterGender::find()->where(['event_id' => $model->event_id])->one();
if (isset($update_gender))
	$model->filter_gender = $update_gender->gender;	

// major
$update_major = EventFilterMajor::find()->where(['event_id' => $model->event_id])->all();
$arrayMajor = [];
$model->filter_major = [];
foreach ($update_major as $value) {
	$arrayMajor[] = $value->major->another->another_name;
}
$model->filter_major = implode(', ', $arrayMajor);

// university
$update_university = EventFilterUniversity::find()->where(['event_id' => $model->event_id])->all();
$arrayUniversity = [];
$model->filter_university = [];
foreach ($update_university as $value) {
$arrayUniversity[] = $value->university->company->directory_i;
}
$model->filter_university = implode(', ', $arrayUniversity);
?>

<div class="event-xxx-view">

<?php
$attributes = [
	'event_id',
	[
		'attribute' => 'publish',
		'value' => $model->publish == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'category_search',
		'value' => $model->category->title->message,
	],
	'title',
	'theme',
	[
		'attribute' => 'introduction',
		'value' => $model->introduction ? $model->introduction : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
		'format'	=> 'html',
	],
	// [
	// 	'attribute' => 'cover_filename',
	// 	'value' => $model->cover_filename ? $model->cover_filename : '-',
	// 	'format'	=> 'html',
	// ],
	[
			'attribute' => 'cover_filename',
			'value' => function ($model) {
					return Html::img(url::Base().'/public/event/admin/cover/'.$model->cover_filename,
					['width' => '400',
						'height' => '300']);
					},
					'format' => 'html',
	],
	// [
	// 	'attribute' => 'banner_filename',
	// 	'value' => $model->banner_filename ? $model->banner_filename : '-',
	// 	'format'	=> 'html',
	// ],
	[
			'attribute' => 'banner_filename',
			'value' => function ($model) {
					return Html::img(url::Base().'/public/event/admin/banner/'.$model->banner_filename,
					['width' => '400',
						'height' => '300']);
					},
					'format' => 'html',
	],
	[
		'attribute' => 'registered_enable',
		'value' => $model->registered_enable == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'registered_message',
		'value' => $model->registered_message ? $model->registered_message : '-',
		'format'	=> 'html',
	],
	// 'registered_type',
	[
		'attribute' => 'registered_type',
		'value' => $model->registered_type ? $model->registered_type : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'tag_id_i',
		'value' => $model->tag_id_i ? $model->tag_id_i : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'enable_filter',
		'value' => $model->enable_filter == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No'),
	],
	[
		'attribute' => 'filter_gender',
		'value' => $model->filter_gender ? $model->filter_gender : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'filter_major',
		'value' => $model->filter_major ? $model->filter_major : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'filter_university',
		'value' => $model->filter_university ? $model->filter_university : '-',
		'format'	=> 'html',
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
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