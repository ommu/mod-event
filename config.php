<?php
/**
 * event module config
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 November 2017, 09:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use ommu\event\Events;
use ommu\event\models\Events as EventsModel;
use ommu\event\models\EventRegistered;

return [
	'id' => 'event',
	'class' => ommu\event\Module::className(),
	'events' => [
		[
			'class'    => EventsModel::className(),
			'event'    => EventsModel::EVENT_BEFORE_SAVE_EVENTS,
			'callback' => [Events::className(), 'onBeforeSaveEvents']
		],
		[
			'class'    => EventRegistered::className(),
			'event'    => EventRegistered::EVENT_BEFORE_SAVE_EVENT_REGISTERED,
			'callback' => [Events::className(), 'onBeforeSaveEventRegistered']
		],
	],
];