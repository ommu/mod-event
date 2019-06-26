<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul event.
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 25 June 2019, 16:08 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event;

use Yii;
use yii\helpers\Inflector;
use app\models\CoreTags;
use ommu\event\models\EventTag;
use ommu\event\models\EventFilterGender;
use ommu\event\models\EventFilterMajor;
use ommu\event\models\EventFilterMajorGroup;
use ommu\event\models\EventFilterUniversity;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSaveEvents($event)
	{
		$model = $event->sender;

		self::setEventTag($model);
		self::setFilterGender($model);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setEventTag($event)
	{
		$oldTag = $event->getTags(true, 'title');
		if($event->tag)
			$tag = explode(',', $event->tag);

		// insert difference tag
		if(is_array($tag)) {
			foreach ($tag as $val) {
				if(in_array($val, $oldTag)) {
					unset($oldTag[array_keys($oldTag, $val)[0]]);
					continue;
				}

				$tagSlug = Inflector::slug($val);
				$tagFind = CoreTags::find()
					->select(['tag_id'])
					->andWhere(['body' => $tagSlug])
					->one();

				if($tagFind != null)
					$tag_id = $tagFind->tag_id;
				else {
					$model = new CoreTags();
					$model->body = $tagSlug;
					if($model->save())
						$tag_id = $model->tag_id;
				}

				$model = new EventTag();
				$model->event_id = $event->id;
				$model->tag_id = $tag_id;
				$model->save();
			}
		}

		// drop difference tag
		if(!empty($oldTag)) {
			foreach ($oldTag as $key => $val) {
				EventTag::find()
					->select(['id'])
					->where(['event_id'=>$event->id, 'tag_id'=>$key])
					->one()
					->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setFilterGender($event)
	{
		$oldGender = array_flip($event->getGenders(true));

		if((empty($oldGender) && !$event->gender) || in_array($event->gender, $oldGender))
			return;

		if($event->gender) {
			if(empty($oldGender)) {
				$model = new EventFilterGender();
				$model->event_id = $event->id;
				$model->gender = $event->gender;
				$model->save();
			} else {
				$model = EventFilterGender::findOne(key($oldGender));
				$model->gender = $event->gender;
				$model->save();
			}

		} else {
			// drop filter gender
			EventFilterGender::find()
				->select(['id'])
				->andWhere(['id'=>key($oldGender)])
				->one()
				->delete();
		}
	}
}