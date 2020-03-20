<?php
/**
 * Events (events)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\AdminController
 * @var $model ommu\event\models\search\Events
 * @var $form yii\widgets\ActiveForm
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
use yii\widgets\ActiveForm;
use ommu\event\models\Events;
use ommu\event\models\EventCategory;
?>

<div class="events-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = EventCategory::getCategory();
		echo $form->field($model, 'cat_id')
			->dropDownList($category, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'title');?>

		<?php echo $form->field($model, 'theme');?>

		<?php echo $form->field($model, 'introduction');?>

		<?php echo $form->field($model, 'description');?>

		<?php echo $form->field($model, 'cover_filename');?>

		<?php echo $form->field($model, 'banner_filename');?>

		<?php echo $form->field($model, 'registered_message');?>

		<?php $registeredType = Events::getRegisteredType();
			echo $form->field($model, 'registered_type')
			->dropDownList($registeredType, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'package_reward');?>

		<?php echo $form->field($model, 'published_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php $registeredEnable = Events::getRegisteredEnable();
			echo $form->field($model, 'registered_enable')
			->dropDownList($registeredEnable, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'enable_filter')
			->dropDownList($model->filterYesNo(), ['prompt'=>'']);?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt'=>'']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>