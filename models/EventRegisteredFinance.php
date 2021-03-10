<?php
/**
 * EventRegisteredFinance
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 29 November 2017, 15:45 WIB
 * @modified date 28 June 2019, 19:02 WIB
 * @link https://github.com/ommu/mod-event
 *
 * This is the model class for table "ommu_event_registered_finance".
 *
 * The followings are the available columns in table "ommu_event_registered_finance":
 * @property integer $registered_id
 * @property integer $price
 * @property integer $payment
 * @property integer $reward
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property EventRegistered $registered
 * @property Users $creation
 *
 */

namespace ommu\event\models;

use Yii;
use app\models\Users;

class EventRegisteredFinance extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname'];

	public $registeredEventId;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_event_registered_finance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['price', 'payment', 'reward'], 'required'],
			[['price', 'payment', 'reward', 'creation_id'], 'integer'],
			[['registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventRegistered::className(), 'targetAttribute' => ['registered_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'registered_id' => Yii::t('app', 'Registered'),
			'price' => Yii::t('app', 'Price'),
			'payment' => Yii::t('app', 'Payment'),
			'reward' => Yii::t('app', 'Reward'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'registeredEventId' => Yii::t('app', 'Registered'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRegistered()
	{
		return $this->hasOne(EventRegistered::className(), ['id' => 'registered_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\query\EventRegisteredFinance the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\event\models\query\EventRegisteredFinance(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['price'] = [
			'attribute' => 'price',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asCurrency($model->price);
			},
		];
		$this->templateColumns['payment'] = [
			'attribute' => 'payment',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asCurrency($model->payment);
			},
		];
		$this->templateColumns['reward'] = [
			'attribute' => 'reward',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asCurrency($model->reward);
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['registered_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function setReward
	 */
	public static function setReward($price, $packageReward)
	{
        if (!$packageReward) {
            return 0;
        }
		
        if ($packageReward['type'] == 1) {
            return $price * ($packageReward['reward']/100);
        }
		
        if ($packageReward['type'] == 0) {
            return ($packageReward['reward'] > $price) ? $price : $packageReward['reward'];
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->registeredEventId = isset($this->registered) ? $this->registered->event->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
