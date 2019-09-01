<?php
/**
 * EventBlastingItem

 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 13:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_blasting_item".
 *
 * The followings are the available columns in table "ommu_event_blasting_item":
 * @property string $id
 * @property string $blast_id
 * @property integer $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property EventBlastingHistory[] $histories
 * @property EventBlastings $blasting
 *
 */

namespace ommu\event\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use ommu\users\models\Users;

class EventBlastingItem extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date'];

	// Search Variable
	public $blasting_search;
	public $user_search;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_blasting_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['blast_id', 'user_id', 'view_ip', 'creation_id'], 'required'],
			[['blast_id', 'user_id', 'views', 'creation_id'], 'integer'],
			[['view_date', 'creation_date', 'modified_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['blast_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventBlastings::className(), 'targetAttribute' => ['blast_id' => 'blast_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'blast_id' => Yii::t('app', 'Blast'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'blasting_search' => Yii::t('app', 'Blasting'),
			'user_search' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories()
	{
		return $this->hasMany(EventBlastingHistory::className(), ['item_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBlasting()
	{
		return $this->hasOne(EventBlastings::className(), ['blast_id' => 'blast_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('blasting')) {
			$this->templateColumns['blasting_search'] = [
				'attribute' => 'blasting_search',
				'value' => function($model, $key, $index, $column) {
					return $model->blasting->blast_id;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user->displayname) ? $model->user->displayname : '-';
				},
			];
		}
		// $this->templateColumns['views'] = 'views';
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['blasting-history/index', 'item'=>$model->primaryKey]);
				return Html::a($model->views ? $model->views : 0, $url);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = 'view_ip';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

}
