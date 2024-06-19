<?php
/**
 * Event Registereds (event-registered)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\AdminController
 * @var $model ommu\event\models\search\EventRegistered
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:11 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\event\models\EventRegistered;
?>

<div class="event-registered-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'eventTitle');?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'confirmation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php $status = EventRegistered::getStatus();
			echo $form->field($model, 'status')
			->dropDownList($status, ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>