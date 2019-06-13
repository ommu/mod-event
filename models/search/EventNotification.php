<?php
/**
 * EventNotification
 *
 * EventNotification represents the model behind the search form about `ommu\event\models\EventNotification`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:41 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventNotification as EventNotificationModel;
//use ommu\event\models\EventBatch;

class EventNotification extends EventNotificationModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'status', 'batch_id', 'notified_id', 'users'], 'integer'],
			[['notified_date', 'creation_date', 'batchName', 'eventTitle', 'notified_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = EventNotificationModel::find()->alias('t');
		else
			$query = EventNotificationModel::find()->alias('t')->select($column);
		$query->joinWith(['batch batch', 'batch.event event', 'notified notified']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['eventTitle'] = [
			'asc' => ['event.title' => SORT_ASC],
			'desc' => ['event.title' => SORT_DESC],
		];
		$attributes['batchName'] = [
			'asc' => ['batch.batch_name' => SORT_ASC],
			'desc' => ['batch.batch_name' => SORT_DESC],
		];
		$attributes['notified_search'] = [
			'asc' => ['notified.displayname' => SORT_ASC],
			'desc' => ['notified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => isset($params['id']) ? $params['id'] : $this->id,
			't.status' => $this->status,
			't.batch_id' => isset($params['batch']) ? $params['batch'] : $this->batch_id,
			'cast(t.notified_date as date)' => $this->notified_date,
			't.notified_id' => $this->notified_id,
			't.users' => $this->users,
			'cast(t.creation_date as date)' => $this->creation_date,
		]);

		$query->andFilterWhere(['like', 'batch.batch_name', $this->batchName])
				->andFilterWhere(['like', 'notified.displayname', $this->notified_search])
				->andFilterWhere(['like', 'event.title', $this->eventTitle]);

		return $dataProvider;
	}
}
