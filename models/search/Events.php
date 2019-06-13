<?php
/**
 * Events
 *
 * Events represents the model behind the search form about `ommu\event\models\Events`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 13:22 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\Events as EventsModel;
//use ommu\event\models\EventCategory;

class Events extends EventsModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['event_id', 'publish', 'cat_id', 'registered_enable', 'enable_filter', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'theme', 'introduction', 'description', 'cover_filename', 'banner_filename', 'registered_message', 'registered_type', 'published_date', 'creation_date', 'modified_date', 'updated_date', 'category_search', 'creationDisplayname', 'modifiedDisplayname', 'blasting_search'], 'safe'],
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
			$query = EventsModel::find()->alias('t');
		else
			$query = EventsModel::find()->alias('t')->select($column);
		$query->joinWith(['category category', 'creation creation', 'modified modified', 'category.name name', 'view view']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['category_search'] = [
			'asc' => ['name.message' => SORT_ASC],
			'desc' => ['name.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['tag_search'] = [
			'asc' => ['view.tags' => SORT_ASC],
			'desc' => ['view.tags' => SORT_DESC],
		];
		$attributes['blasting_search'] = [
			'asc' => ['view.blasting_condition' => SORT_ASC],
			'desc' => ['view.blasting_condition' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['event_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.event_id' => isset($params['id']) ? $params['id'] : $this->event_id,
			't.publish' => isset($params['publish']) ? 1 : $this->publish,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.registered_enable' => $this->registered_enable,
			't.enable_filter' => $this->enable_filter,
			'cast(t.published_date as date)' => $this->published_date,
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

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.theme', $this->theme])
			->andFilterWhere(['like', 't.introduction', $this->introduction])
			->andFilterWhere(['like', 't.description', $this->description])
			->andFilterWhere(['like', 't.cover_filename', $this->cover_filename])
			->andFilterWhere(['like', 't.banner_filename', $this->banner_filename])
			->andFilterWhere(['like', 't.registered_message', $this->registered_message])
			->andFilterWhere(['like', 't.registered_type', $this->registered_type])
			->andFilterWhere(['like', 'name.message', $this->category_search])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		if (isset($this->blasting_search)) {
			if ($this->blasting_search == '' || $this->blasting_search == 1)
			$query->andFilterWhere(['view.blasting_condition' => $this->blasting_search]);
			else
			$query->andWhere(['view.blasting_condition' => null]);
		}

		return $dataProvider;
	}
}
