<?php
/**
 * EventFilterGender
 *
 * EventFilterGender represents the model behind the search form about `ommu\event\models\EventFilterGender`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 28 November 2017, 09:15 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventFilterGender as EventFilterGenderModel;
//use ommu\event\models\Events;

class EventFilterGender extends EventFilterGenderModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['filter_id', 'event_id', 'creation_id'], 'integer'],
			[['gender', 'creation_date', 'eventTitle', 'creationDisplayname'], 'safe'],
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
			$query = EventFilterGenderModel::find()->alias('t');
		else
			$query = EventFilterGenderModel::find()->alias('t')->select($column);
		$query->joinWith(['event event', 'creation creation']);

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
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['filter_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.filter_id' => isset($params['id']) ? $params['id'] : $this->filter_id,
			't.event_id' => isset($params['event']) ? $params['event'] : $this->event_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
		]);

		$query->andFilterWhere(['like', 't.gender', $this->gender])
			->andFilterWhere(['like', 'event.title', $this->eventTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
