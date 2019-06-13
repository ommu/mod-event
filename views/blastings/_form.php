<?php
/**
 * Event Blastings (event-blastings)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\BlastingsController
 * @var $model ommu\event\models\EventBlastings
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 8 December 2017, 15:02 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use app\modules\blasting\models\BlastingFilter;
use ommu\event\models\Events;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
// cek apakah dari event atau tidak
if (($event = Yii::$app->request->get('event')) != null)
	$event = $event;
else if (($event_id = Yii::$app->request->get('event_id')) != null)
	$event = $event_id;

if ($event != null){
	$model->event_id = $event;

	echo $form->field($model, 'event_id')->hiddenInput()
	->label(false);
?>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eventblastings-event_id"><?php echo Yii::t('app', 'Event');?></label>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<?php 
			echo $model->event->title;
			?>
		</div>
	</div>
<?php
}
else {
// bila tidak dari event maka pilih event melalui dropdown
$get_event = Events::getEvent(1);
echo $form->field($model, 'event_id')
	->dropDownList($get_event, ['prompt' => ''])
	->label($model->getAttributeLabel('event_id')); 
}
?>

<?php /* echo $form->field($model, 'filter_id')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('filter_id')); */?>

<div class="form-group field-eventblastings-filter_i required">
	<?php 
	echo $form->field($model, 'filter_i', ['template' => '{label}', 'options' => ['tag' => null]])
		->label($model->getAttributeLabel('filter_i')); 
		?>
	<div class="col-md-9 col-sm-9 col-xs-12">
		<?php 
		// cek apakah memilih filter yang sudah ada atau membuat baru
		if (($filter_id = Yii::$app->request->get('filter_id')) != null) {
			$blasting_filter = new BlastingFilter();
			$model->filter_i['gender'] = $blasting_filter->getFilter($filter_id)['gender'];
		} else {
			if(!$model->getErrors())
				$model->filter_i = unserialize($model->filter_i);
			if(empty($model->filter_i))
				$model->filter_i = [];
		}
		echo Html::label($model->getAttributeLabel('filter_i[gender]'), null); ?>
		<?php 
		// cek apakah sedang memilih filter atau akan mengirim blasting
		if (Yii::$app->request->get('blast_id')) {
			echo $form->field($model, 'filter_i[gender]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12 ">{input}{error}</div>', 'options' => ['tag' => null]])
				->checkboxList([
					'male' => 'Male', 
					'female' => 'Female'
				], ['class'=>'desc pt-10', 'separator' => '<br />', 'onclick' => "return false;"])
				->label($model->getAttributeLabel('filter_i')); 
		} else {
			echo $form->field($model, 'filter_i[gender]', ['template' => '<div class="col-md-4 col-sm-4 col-xs-12 ">{input}{error}</div>', 'options' => ['tag' => null]])
				->checkboxList([
					'male' => 'Male', 
					'female' => 'Female'
				], ['class'=>'desc pt-10', 'separator' => '<br />'])
				->label($model->getAttributeLabel('filter_i')); 
		}
		?>
	</div>
</div>

<?php /* echo $form->field($model, 'users')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('users')); ?>

<?php echo $form->field($model, 'blast_with')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('blast_with')); */?>

<div class="ln_solid"></div>

<?php // cek apakah sedang memilih filter atau akan mengirim blasting
if (!Yii::$app->request->get('blast_id')) {
	echo $form->field($model, 'submitButton')
		->submitButton();
}
?>

<?php ActiveForm::end(); ?>