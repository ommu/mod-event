<?php
/**
 * Event Blasting Items (event-blasting-item)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\ItemController
 * @var $model ommu\event\models\search\EventBlastingItem
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'id'); ?>

		<?php echo $form->field($model, 'blast_id'); ?>

		<?php echo $form->field($model, 'user_id'); ?>

		<?php echo $form->field($model, 'views'); ?>

		<?php echo $form->field($model, 'view_date')
			->input('date');?>

		<?php echo $form->field($model, 'view_ip'); ?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
