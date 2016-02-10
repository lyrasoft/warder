<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry;

use Windwalker\Sentry\Listener\UserListener;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Event\Dispatcher;

/**
 * The UserPackage class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SentryPackage extends AbstractPackage
{
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

		$dispatcher->addListener(new UserListener);
	}
}
