<?php
/**
 * Events
 *
 * This is the ActiveQuery class for [[\ommu\event\models\Events]].
 * @see \ommu\event\models\Events
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 24 June 2019, 08:55 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class Events extends \yii\db\ActiveQuery
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
	 * @return \ommu\event\models\Events[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\Events|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
