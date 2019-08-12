<?php
/**
 * Event User Banneds (event-user-banned)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\UserBannedController
 * @var $model ommu\event\models\EventUserBanned
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event User Banneds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->banned_id, 'url' => ['view', 'id'=>$model->banned_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'View'), 'url' => Url::to(['view', 'id'=>$model->banned_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->banned_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="event-xxx-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
