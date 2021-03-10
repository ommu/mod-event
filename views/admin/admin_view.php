<?php
/**
 * Events (events)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\Events
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['admin/publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'published_date',
		'value' => Yii::$app->formatter->asDate($model->published_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
	],
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
		'attribute' => 'introduction',
		'value' => $model->introduction ? $model->introduction : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'description',
		'value' => $model->description ? $model->description : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'cover_filename',
		'value' => function ($model) {
			$uploadPath = join('/', [Events::getUploadPath(false), $model->id]);
			return $model->cover_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->cover_filename])), ['alt' => $model->cover_filename, 'class' => 'd-block border border-width-3 mb-4']).$model->cover_filename : '-';
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'banner_filename',
		'value' => function ($model) {
			$uploadPath = join('/', [Events::getUploadPath(false), $model->id]);
			return $model->banner_filename ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->banner_filename])), ['alt' => $model->banner_filename, 'class' => 'd-block border border-width-3 mb-4']).$model->banner_filename : '-';
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'theme',
		'value' => $model->theme ? $model->theme : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'tag',
		'value' => implode(', ', $model->getTags(true, 'title')),
		'visible' => !$small,
	],
	[
		'attribute' => 'registered_enable',
		'value' => $model->quickAction(Url::to(['admin/registered', 'id' => $model->primaryKey]), $model->registered_enable, 'Enable,Disable'),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'registered_type',
		'value' => Events::getRegisteredType($model->registered_type),
	],
	[
		'attribute' => 'package_reward',
		'value' => $model->isFree == true ? Yii::t('app', 'Free') : Events::parseReward($model->package_reward),
	],
	[
		'attribute' => 'registered_message',
		'value' => serialize($model->registered_message),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'enable_filter',
		'value' => $model->getRegisteredEnable($model->enable_filter),
		'visible' => !$small,
	],
	[
		'attribute' => 'gender',
		'value' => $model::parseGender(array_flip($model->getGenders(true)), ', '),
		'visible' => !$small,
	],
	[
		'attribute' => 'major',
		'value' => implode(', ', $model->getMajors(true, 'title')),
		'visible' => !$small,
	],
	[
		'attribute' => 'majorGroup',
		'value' => implode(', ', $model->getMajorGroups(true, 'title')),
		'visible' => !$small,
	],
	[
		'attribute' => 'university',
		'value' => implode(', ', $model->getUniversities(true, 'title')),
		'visible' => !$small,
	],
	[
		'attribute' => 'batches',
		'value' => function ($model) {
			$batches = $model->getBatches('count');
			return Html::a($batches, ['o/batch/manage', 'id' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} batches', ['count' => $batches])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'registereds',
		'value' => function ($model) {
			$registereds = $model->getRegistereds(true);
			return Html::a($registereds, ['registered/admin/manage', 'id' => $model->primaryKey], ['title' => Yii::t('app', '{count} registereds', ['count' => $registereds])]);
		},
		'format' => 'html',
		'visible' => !$small,
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
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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