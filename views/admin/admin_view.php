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
 * @modified date 24 June 2019, 10:28 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\event\models\Events;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="events-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
	[
		'attribute' => 'categoryName',
		'value' => function ($model) {
			$categoryName = isset($model->category) ? $model->category->title->message : '-';
			if($categoryName != '-')
				return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName, 'class'=>'modal-btn']);
			return $categoryName;
		},
		'format' => 'html',
	],
	'title',
	'theme',
	[
		'attribute' => 'introduction',
		'value' => $model->introduction ? $model->introduction : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'cover_filename',
		'value' => function ($model) {
			$uploadPath = join('/', [Events::getUploadPath(false), $model->id]);
			return $model->cover_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover_filename])), ['class'=>'mb-3']).$model->cover_filename : '-';
		},
		'format' => 'html',
	],
	[
		'attribute' => 'banner_filename',
		'value' => function ($model) {
			$uploadPath = join('/', [Events::getUploadPath(false), $model->id]);
			return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['class'=>'mb-3']).$model->banner_filename : '-';
		},
		'format' => 'html',
	],
	[
		'attribute' => 'tag',
		'value' => implode(', ', $model->getTags(true, 'title')),
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
	],
	[
		'attribute' => 'registered_enable',
		'value' => $model->quickAction(Url::to(['registered', 'id'=>$model->primaryKey]), $model->registered_enable, 'Enable,Disable'),
		'format' => 'raw',
	],
	[
		'attribute' => 'registered_type',
		'value' => Events::getRegisteredType($model->registered_type),
	],
	[
		'attribute' => 'package_reward',
		'value' => Events::parseReward($model->package_reward),
	],
	[
		'attribute' => 'registered_message',
		'value' => serialize($model->registered_message),
		'format' => 'html',
	],
	[
		'attribute' => 'enable_filter',
		'value' => $model->filterYesNo($model->enable_filter),
	],
	[
		'attribute' => 'gender',
		'value' => $model::parseGender(array_flip($model->getGenders(true)), ', '),
	],
	[
		'attribute' => 'major',
		'value' => implode(', ', $model->getMajors(true, 'title')),
	],
	[
		'attribute' => 'majorGroup',
		'value' => implode(', ', $model->getMajorGroups(true, 'title')),
	],
	[
		'attribute' => 'university',
		'value' => implode(', ', $model->getUniversities(true, 'title')),
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
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
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