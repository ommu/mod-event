<?php
/**
 * Event Notifications (event-notification)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\NotificationController
 * @var $model ommu\event\models\search\EventNotification
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 29 November 2017, 15:41 WIB
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

		<?php echo $form->field($model, 'status'); ?>

		<?php echo $form->field($model, 'batch_id'); ?>

		<?php echo $form->field($model, 'notified_date')
			->input('date');?>

		<?php echo $form->field($model, 'notified_id'); ?>

		<?php echo $form->field($model, 'users'); ?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
