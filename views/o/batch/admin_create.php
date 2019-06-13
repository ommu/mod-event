<?php
/**
 * Event Batches (event-batch)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\o\BatchController
 * @var $model ommu\event\models\EventBatch
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:40 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="event-xxx-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'events' => $events,
]); ?>

</div>