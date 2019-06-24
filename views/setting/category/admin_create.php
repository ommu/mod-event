<?php
/**
 * Event Categories (event-category)
 * @var $this app\components\View
 * @var $this ommu\event\controllers\setting\CategoryController
 * @var $model ommu\event\models\EventCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 09:46 WIB
 * @modified date 23 June 2019, 20:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="event-category-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
