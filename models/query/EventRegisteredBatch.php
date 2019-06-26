<?php
/**
 * EventRegisteredBatch
 *
 * This is the ActiveQuery class for [[\ommu\event\models\EventRegisteredBatch]].
 * @see \ommu\event\models\EventRegisteredBatch
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 26 June 2019, 21:42 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event\models\query;

class EventRegisteredBatch extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventRegisteredBatch[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\event\models\EventRegisteredBatch|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
