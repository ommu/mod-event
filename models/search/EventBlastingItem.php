<?php
/**
 * EventBlastingItem
 *
 * EventBlastingItem represents the model behind the search form about `ommu\event\models\EventBlastingItem`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 8 December 2017, 15:04 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventBlastingItem as EventBlastingItemModel;
//use ommu\event\models\EventBlastings;

class EventBlastingItem extends EventBlastingItemModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'blast_id', 'user_id', 'views', 'creation_id'], 'integer'],
			[['view_date', 'view_ip', 'creation_date', 'modified_date', 'blasting_search', 'userDisplayname', 'creationDisplayname'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = EventBlastingItemModel::find()->alias('t');
        } else {
            $query = EventBlastingItemModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'blasting blasting', 
			'user user', 
			'creation creation',
		])
		->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['blasting_search'] = [
			'asc' => ['blasting.blast_id' => SORT_ASC],
			'desc' => ['blasting.blast_id' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => isset($params['id']) ? $params['id'] : $this->id,
			't.blast_id' => isset($params['blasting']) ? $params['blasting'] : $this->blast_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.views' => $this->views,
			'cast(t.view_date as date)' => $this->view_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'blasting.blast_id', $this->blasting_search])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
