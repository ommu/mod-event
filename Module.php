<?php
/**
 * event module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 23 November 2017, 09:31 WIB
 * @link https://github.com/ommu/mod-event
 *
 */

namespace ommu\event;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'ommu\event\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
        parent::init();

		// custom initialization code goes here
	}
}
