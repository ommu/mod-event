<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\search\EventBatch
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 09:40 WIB
 * @modified date 26 June 2019, 14:39 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\event\models\EventBatch;
?>

<div class="event-batch-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'eventTitle');?>

		<?php echo $form->field($model, 'batch_name');?>

		<?php echo $form->field($model, 'batch_desc');?>

		<?php echo $form->field($model, 'batch_date')
			->input('date');?>

		<?php echo $form->field($model, 'batch_time');?>

		<?php echo $form->field($model, 'batch_price');?>

		<?php echo $form->field($model, 'batch_location');?>

		<?php echo $form->field($model, 'location_name');?>

		<?php echo $form->field($model, 'location_address');?>

		<?php echo $form->field($model, 'registered_limit');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>