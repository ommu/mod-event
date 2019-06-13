<?php
/**
 * EventUserBanned
 *
 * EventUserBanned represents the model behind the search form about `ommu\event\models\EventUserBanned`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 7 December 2017, 10:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventUserBanned as EventUserBannedModel;

class EventUserBanned extends EventUserBannedModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['banned_id', 'status', 'event_id', 'user_id', 'unbanned_id', 'creation_id', 'modified_id'], 'integer'],
			[['banned_start', 'banned_end', 'banned_desc', 'unbanned_agreement', 'unbanned_date', 'modified_date', 'user_search', 'creationDisplayname', 'modifiedDisplayname', 'unbanned_search', 'eventTitle'], 'safe'],
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
			$query = EventUserBannedModel::find()->alias('t');
		else
			$query = EventUserBannedModel::find()->alias('t')->select($column);
		$query->joinWith(['user user', 'creation creation', 'modified modified', 'unbanned unbanned', 'event event']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
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
		$attributes['unbanned_search'] = [
			'asc' => ['unbanned.displayname' => SORT_ASC],
			'desc' => ['unbanned.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['banned_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.banned_id' => isset($params['id']) ? $params['id'] : $this->banned_id,
			't.status' => $this->status,
			't.event_id' => isset($params['event']) ? $params['event'] : $this->event_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.banned_start as date)' => $this->banned_start,
			'cast(t.banned_end as date)' => $this->banned_end,
			'cast(t.unbanned_date as date)' => $this->unbanned_date,
			't.unbanned_id' => isset($params['unbanned']) ? $params['unbanned'] : $this->unbanned_id,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.banned_desc', $this->banned_desc])
			->andFilterWhere(['like', 't.unbanned_agreement', $this->unbanned_agreement])
			->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'event.title', $this->eventTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'unbanned.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
