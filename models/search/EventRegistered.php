<?php
/**
 * EventRegistered
 *
 * EventRegistered represents the model behind the search form about `ommu\event\models\EventRegistered`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 29 November 2017, 15:43 WIB
 * @modified date 28 June 2019, 19:12 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventRegistered as EventRegisteredModel;

class EventRegistered extends EventRegisteredModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'status', 'event_id', 'user_id', 'creation_id', 'modified_id', 'eventCategoryId'], 'integer'],
			[['confirmation_date', 'creation_date', 'modified_date', 'eventTitle', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname', 'price', 'payment', 'reward', 'batch'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
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
			$query = EventRegisteredModel::find()->alias('t');
		else
			$query = EventRegisteredModel::find()->alias('t')->select($column);
		$query->joinWith([
			'event event', 
			'user user', 
			'creation creation', 
			'modified modified',
			'event.category.title category',
			'finance finance', 
			'batches batches',
			'batches.batch batchesRltn',
		])
		->groupBy(['id']);

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
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['eventCategoryId'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['price'] = [
			'asc' => ['finance.price' => SORT_ASC],
			'desc' => ['finance.price' => SORT_DESC],
		];
		$attributes['payment'] = [
			'asc' => ['finance.payment' => SORT_ASC],
			'desc' => ['finance.payment' => SORT_DESC],
		];
		$attributes['reward'] = [
			'asc' => ['finance.reward' => SORT_ASC],
			'desc' => ['finance.reward' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.status' => $this->status,
			't.event_id' => isset($params['event']) ? $params['event'] : $this->event_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.confirmation_date as date)' => $this->confirmation_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'event.cat_id' => $this->eventCategoryId,
		]);

		if(isset($params['batchId']) && $params['batchId'])
			$query->andFilterWhere(['batches.batch_id' => $params['batchId']]);

		$query->andFilterWhere(['like', 'event.title', $this->eventTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'finance.price', $this->price])
			->andFilterWhere(['like', 'finance.payment', $this->payment])
			->andFilterWhere(['like', 'finance.reward', $this->reward])
			->andFilterWhere(['like', 'batchesRltn.batch_name', $this->batch]);

		return $dataProvider;
	}
}
