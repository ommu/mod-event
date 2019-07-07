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
 * @modified date 24 June 2019, 10:28 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\Events as EventsModel;

class Events extends EventsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'cat_id', 'registered_enable', 'enable_filter', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'theme', 'introduction', 'description', 'cover_filename', 'banner_filename', 'registered_message', 'registered_type', 'package_reward', 'published_date', 'creation_date', 'modified_date', 'updated_date', 'categoryName', 'creationDisplayname', 'modifiedDisplayname', 'tag', 'gender', 'major', 'majorGroup', 'university'], 'safe'],
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
			$query = EventsModel::find()->alias('t');
		else
			$query = EventsModel::find()->alias('t')->select($column);
		$query->joinWith([
			'category.title category',
			'creation creation',
			'modified modified',
			'tags tags',
			'genders genders',
			'majors majors',
			'majorGroups majorGroups',
			'universities universities',
			'tags.tag tagsRltn',
			'majors.major majorsRltn',
			'majorGroups majorGroupsRltn',
			'universities.university.company universitiesRltn',
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
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
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
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.registered_enable' => $this->registered_enable,
			't.registered_type' => $this->registered_type,
			't.enable_filter' => $this->enable_filter,
			'cast(t.published_date as date)' => $this->published_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'genders.gender' => $this->gender,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		if(isset($params['tagId']) && $params['tagId'])
			$query->andFilterWhere(['tags.tag_id' => $params['tagId']]);

		if(isset($params['majorId']) && $params['majorId'])
			$query->andFilterWhere(['majors.major_id' => $params['majorId']]);

		if(isset($params['majorGroupId']) && $params['majorGroupId'])
			$query->andFilterWhere(['majorGroups.major_group_id' => $params['majorGroupId']]);

		if(isset($params['universityId']) && $params['universityId'])
			$query->andFilterWhere(['universities.university_id' => $params['universityId']]);

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.theme', $this->theme])
			->andFilterWhere(['like', 't.introduction', $this->introduction])
			->andFilterWhere(['like', 't.description', $this->description])
			->andFilterWhere(['like', 't.cover_filename', $this->cover_filename])
			->andFilterWhere(['like', 't.banner_filename', $this->banner_filename])
			->andFilterWhere(['like', 't.registered_message', $this->registered_message])
			->andFilterWhere(['like', 't.package_reward', $this->package_reward])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'tagsRltn.body', $this->tag])
			->andFilterWhere(['like', 'majorsRltn.major_name', $this->major])
			->andFilterWhere(['like', 'majorGroupsRltn.group_name', $this->majorGroup])
			->andFilterWhere(['like', 'universitiesRltn.company_name', $this->university]);

		return $dataProvider;
	}
}
