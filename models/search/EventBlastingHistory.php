<?php
/**
 * EventBlastingHistory
 *
 * EventBlastingHistory represents the model behind the search form about `ommu\event\models\EventBlastingHistory`.
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
use ommu\event\models\EventBlastingHistory as EventBlastingHistoryModel;
//use ommu\event\models\EventBlastingItem;

class EventBlastingHistory extends EventBlastingHistoryModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'item_id'], 'integer'],
			[['view_date', 'view_ip', 'item_search'], 'safe'],
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
            $query = EventBlastingHistoryModel::find()->alias('t');
        } else {
            $query = EventBlastingHistoryModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'item item',
		]);

		$query->groupBy(['id']);

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
		$attributes['item_search'] = [
			'asc' => ['item.id' => SORT_ASC],
			'desc' => ['item.id' => SORT_DESC],
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
			't.item_id' => isset($params['item']) ? $params['item'] : $this->item_id,
			'cast(t.view_date as date)' => $this->view_date,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'item.id', $this->item_search]);

		return $dataProvider;
	}
}
