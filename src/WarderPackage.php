<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder;

use Windwalker\Core\Package\PackageHelper;
use Windwalker\Warder\Helper\WarderHelper;
use Windwalker\Warder\Listener\WarderListener;
use Windwalker\Warder\Listener\UserListener;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Event\Dispatcher;

define('WARDER_ROOT', dirname(__DIR__));
define('WARDER_SOURCE', WARDER_ROOT . '/src');
define('WARDER_TEMPLATES', WARDER_ROOT . '/templates');

/**
 * The UserPackage class.
 *
 * @since  {DEPLOY_VERSION}
 */
class WarderPackage extends AbstractPackage
{
	/**
	 * WarderPackage constructor.
	 */
	public function __construct()
	{
		WarderHelper::setPackage($this);
	}

	/**
	 * initialise
	 *
	 * @throws  \LogicException
	 * @return  void
	 */
	public function initialise()
	{
		parent::initialise();
	}

	/**
	 * registerListeners
	 *
	 * @param Dispatcher $dispatcher
	 *
	 * @return  void
	 */
	public function registerListeners(Dispatcher $dispatcher)
	{
		parent::registerListeners($dispatcher);

		$dispatcher->addListener(new UserListener($this))
			->addListener(new WarderListener);
	}
}
