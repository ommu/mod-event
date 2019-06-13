<?php
/**
 * Event Registered Finances (event-registered-finance)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\registered\FinanceController
 * @var $model ommu\event\models\EventRegisteredFinance
 * @var $form app\components\widgets\ActiveForm
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

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Registered Finances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->registered_id, 'url' => ['view', 'id' => $model->registered_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id' => $model->registered_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->registered_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="event-xxx-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
