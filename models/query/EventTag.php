<?php
/**
 * EventTag
 *
 * This is the ActiveQuery class for [[\ommu\event\models\EventTag]].
 * @see \ommu\event\models\EventTag
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 23 June 2019, 15:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class EventTag extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventTag[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventTag|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
