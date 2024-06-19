<?php
/**
 * EventBatch
 *
 * This is the ActiveQuery class for [[\ommu\event\models\EventBatch]].
 * @see \ommu\event\models\EventBatch
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 23 June 2019, 15:33 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class EventBatch extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventBatch[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventBatch|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
