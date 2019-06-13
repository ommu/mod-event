<?php
/**
 * Event Blasting Histories (event-blasting-history)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\BlastingHistoryController
 * @var $model ommu\event\models\search\EventBlastingHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
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

		<?php echo $form->field($model, 'item_id'); ?>

		<?php echo $form->field($model, 'view_date')
			->input('date');?>

		<?php echo $form->field($model, 'view_ip'); ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
