<?php
/**
 * Event Blasting Items (event-blasting-item)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\blasting\ItemController
 * @var $model ommu\event\models\EventBlastingItem
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Blasting Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="event-xxx-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
