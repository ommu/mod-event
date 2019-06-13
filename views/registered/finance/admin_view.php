<?php
/**
 * Event Registered Finances (event-registered-finance)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\FinanceController
 * @var $model ommu\event\models\EventRegisteredFinance
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:46 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Registered Finances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->registered_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->registered_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="event-xxx-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'registered_search',
			'value' => $model->registered->id,
		],
		[
			'attribute' => 'eventTitle',
			'value' => $model->registered->event->title,
		],
		[
			'attribute' => 'userDisplayname',
			'value' => $model->registered->user->displayname,
		],
		[
			'attribute' => 'payment',
			'value' => $model->payment,
			'format'=>['decimal', 0],
		],
		[
			'attribute' => 'reward',
			'value' => $model->reward,
			'format'=>['decimal', 0],
		],
		[
			'attribute' => 'creation_date',
			'value' => !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'datetime') : '-',
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => $model->creation_id ? $model->creation->displayname : '-',
		],
	],
]); ?>

</div>