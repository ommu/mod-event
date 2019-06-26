<?php
/**
 * EventBatch
 *
 * EventBatch represents the model behind the search form about `ommu\event\models\EventBatch`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:40 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventBatch as EventBatchModel;
//use ommu\event\models\Events;

class EventBatch extends EventBatchModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'event_id', 'registered_limit', 'creation_id', 'modified_id'], 'integer'],
			[['batch_name', 'batch_date', 'batch_time', 'creation_date', 'modified_date', 'updated_date', 'eventTitle', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
			$query = EventBatchModel::find()->alias('t');
		else
			$query = EventBatchModel::find()->alias('t')->select($column);
		$query->joinWith(['event event', 'creation creation', 'modified modified', 'view view']);

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
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['adviser_search'] = [
			'asc' => ['view.advisers' => SORT_ASC],
			'desc' => ['view.advisers' => SORT_DESC],
		];
		$attributes['adviser_all_search'] = [
			'asc' => ['view.adviser_all' => SORT_ASC],
			'desc' => ['view.adviser_all' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.event_id' => isset($params['event']) ? $params['event'] : $this->event_id,
			'cast(t.batch_date as date)' => $this->batch_date,
			't.registered_limit' => $this->registered_limit,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(!isset($params['trash']))
			$query->andFilterWhere(['IN', 't.publish', [0,1]]);
		else
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);

		$query->andFilterWhere(['like', 't.batch_name', $this->batch_name])
			->andFilterWhere(['like', 't.batch_time', $this->batch_time])
			->andFilterWhere(['like', 'event.title', $this->eventTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
