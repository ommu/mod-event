<?php
/**
 * EventFilterMajorGroup
 *
 * This is the ActiveQuery class for [[\ommu\event\models\EventFilterMajorGroup]].
 * @see \ommu\event\models\EventFilterMajorGroup
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 24 June 2019, 13:20 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class EventFilterMajorGroup extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventFilterMajorGroup[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventFilterMajorGroup|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
