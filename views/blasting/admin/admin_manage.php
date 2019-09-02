<?php
/**
 * Event Blastings (event-blastings)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\AdminController
 * @var $model ommu\event\models\EventBlastings
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 December 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use ommu\users\models\Users;
use app\components\widgets\ActiveForm;

// form
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Blastings'), 'url' => ['index']];

?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode('Event Blastings Filter'); ?></h2>
			
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo $this->render('_form', [
				'model' => $model,
			]); ?>
		</div>
	</div>
</div>

<!-- User List -->
<?php 
if (($filter_id = Yii::$app->request->get('filter_id')) != null && ($blast_id = Yii::$app->request->get('blast_id')) != null) {

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<?php if(Yii::$app->session->hasFlash('blast_success'))
		echo $this->flashMessage(Yii::$app->session->getFlash('blast_success'));
	else if(Yii::$app->session->hasFlash('blast_error'))
		echo $this->flashMessage(Yii::$app->session->getFlash('blast_error'), 'danger');?>

	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode('User List'); ?></h2>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<?php if($this->params['menu']['option']):?>
				<li class="dropdown">
					<a href="#" title="<?php echo Yii::t('app', 'Options');?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					<?php echo MenuOption::widget(['items' => $this->params['menu']['option']]);?>
				</li>
				<?php endif;?>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php
			// kondisi user yang akan di blast
			$blast = \ommu\event\models\EventBlastings::findOne($blast_id);
			$filter = \app\modules\blasting\models\BlastingFilter::findOne($blast->filter_id);
			$filter_gender = unserialize($filter->filter_value);

			$form2 = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
				'action' => 'blast?blast_id='.$blast_id,
				'enableClientValidation' => true,
				'enableAjaxValidation' => false,
				//'enableClientScript' => true,
			]); 
			
			$dataProviderUser = new yii\data\ActiveDataProvider([
				'query' => Users::find()->select('ommu_users.user_id, ommu_users.displayname')
						->leftJoin('ommu_cv_bio', '`ommu_users`.`user_id` = `ommu_cv_bio`.`user_id`')
						->where(['in', 'ommu_cv_bio.gender', $filter_gender['gender']]),
				'pagination' => [
					'pageSize' => 20,
				],
			]);
			$dataProviderUser->pagination->pageParam = 'user-page';
			$dataProviderUser->sort->sortParam = 'user-sort';
			echo GridView::widget([
				'dataProvider' => $dataProviderUser,
				'columns' => [
					[
						'class' => 'yii\grid\SerialColumn',
						'header' => 'No'
					],
					[
						'header' => 'User',
						'value' => 'displayname'
					],
					[
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Yii::t('app', 'Pilih'),
						'name' => 'user_list'
					],
				],
			]);
			?>
			<?php echo Html::a(Yii::t('app', 'Blast All'), ['blast-all', 'blast_id' => $blast_id], ['class' => 'btn btn-success']); ?>
			<?php echo Html::submitButton(Yii::t('app', 'Blast Selected'), ['class' => 'btn btn-success']); ?>
			<?php ActiveForm::end(); ?>
			<?php echo Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' =>'btn btn-primary']); ?>
		</div>
	</div>
</div>
<?php
}
?>

<!-- index -->
<?php
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
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
	'buttons' => [
		'filter' => function ($url, $model, $key) {
			// cek apakah ada event atau tidak
			if (($event_id = Yii::$app->request->get('event_id')) != null)
				$url = Url::to(['index', 'event_id' => $event_id, 'filter_id' => $model->filter_id]);
			else
				$url = Url::to(['index', 'filter_id' => $model->filter_id]);
			return Html::a('<span class="glyphicon glyphicon-send"></span>', $url, ['title'=>Yii::t('app', 'Use Blasting Filter')]);
		},
		'view' => function ($url, $model, $key) {
			$url = Url::to(['view', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'View Event Blastings')]);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Event Blastings'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{filter} {view} {delete}',
]);
$dataProvider->pagination->pageParam = 'blasting-page';
$dataProvider->sort->sortParam = 'blasting-sort';
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>