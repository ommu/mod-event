<?php
/**
 * EventFilterUniversity
 *
 * This is the ActiveQuery class for [[\ommu\event\models\EventFilterUniversity]].
 * @see \ommu\event\models\EventFilterUniversity
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 24 June 2019, 13:21 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class EventFilterUniversity extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventFilterUniversity[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventFilterUniversity|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
