<?php
/**
 * EventFilterMajor
 *
 * EventFilterMajor represents the model behind the search form about `ommu\event\models\EventFilterMajor`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 28 November 2017, 09:22 WIB
 * @modified date 24 June 2019, 20:13 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\event\models\EventFilterMajor as EventFilterMajorModel;

class EventFilterMajor extends EventFilterMajorModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'event_id', 'major_id', 'creation_id'], 'integer'],
			[['creation_date', 'eventTitle', 'majorName', 'creationDisplayname'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = EventFilterMajorModel::find()->alias('t');
        } else {
            $query = EventFilterMajorModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'event event', 
			'major major', 
			'creation creation'
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
		$attributes['eventTitle'] = [
			'asc' => ['event.title' => SORT_ASC],
			'desc' => ['event.title' => SORT_DESC],
		];
		$attributes['majorName'] = [
			'asc' => ['major.major_name' => SORT_ASC],
			'desc' => ['major.major_name' => SORT_DESC],
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
			't.id' => $this->id,
			't.event_id' => isset($params['event']) ? $params['event'] : $this->event_id,
			't.major_id' => isset($params['major']) ? $params['major'] : $this->major_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
		]);

		$query->andFilterWhere(['like', 'event.title', $this->eventTitle])
			->andFilterWhere(['like', 'major.major_name', $this->majorName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
