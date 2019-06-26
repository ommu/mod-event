<?php
/**
 * event module config
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 23 November 2017, 09:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

use ommu\event\Events;
use ommu\event\models\Events as EventsModel;

return [
	'id' => 'event',
	'class' => ommu\event\Module::className(),
	'events' => [
		[
			'class'    => EventsModel::className(),
			'event'    => EventsModel::EVENT_BEFORE_SAVE_EVENTS,
			'callback' => [Events::className(), 'onBeforeSaveEvents']
		],
	],
];