<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Listener;

use Windwalker\Event\Event;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Utilities\Queue\Priority;

/**
 * The SentryListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SentryListener
{
	/**
	 * onViewBeforeRender
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onViewBeforeRender(Event $event)
	{
		$view = $event['view'];

		/** @var BladeRenderer $renderer */
		$name = $view->getName();
		$renderer = $view->getRenderer();

		$renderer->addPath(SENTRY_SOURCE . '/Templates/' . $name, Priority::LOW - 25);
	}
}
