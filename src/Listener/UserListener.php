<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Listener;

use Windwalker\Sentry\Authentication\Method\SentryMethod;
use Windwalker\Sentry\Handler\UserHandler;
use Windwalker\Authentication\Authentication;
use Windwalker\Core\Authentication\User;
use Windwalker\Event\Event;
use Windwalker\Ioc;

/**
 * The UserListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserListener
{
	/**
	 * onUserAfterLogin
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onUserAfterLogin(Event $event)
	{
		$options = $event['options'];

		$remember = $options['remember'];

		if ($remember)
		{
			$session = Ioc::getSession();

			$uri = Ioc::get('uri');

			setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 100, $session->getOption('cookie_path', $uri['base.path']), $session->getOption('cookie_domain'));
		}
	}

	/**
	 * onLoadAuthenticationMethods
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onLoadAuthenticationMethods(Event $event)
	{
		/** @var Authentication $auth */
		$auth = $event['authentication'];

		$auth->removeMethod('database');
		$auth->addMethod('sentry', new SentryMethod);
	}

	/**
	 * onAfterInitialise
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onAfterInitialise(Event $event)
	{
		User::setHandler(new UserHandler);
	}

	/**
	 * onUserAuthorisation
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onUserAuthorisation(Event $event)
	{
//		$user = $event['user'];
//
//		if ($user->blocked)
//		{
//			$event['result'] = false;
//
//			return;
//		}
//
//		if ($user->activation)
//		{
//			$event['result'] = false;
//
//			return;
//		}
	}

	/**
	 * onViewBeforeRender
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onViewBeforeRender(Event $event)
	{
//		$data = $event['data'];
//
//		$data->user = User::get();
	}
}
