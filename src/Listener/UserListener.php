<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Listener;

use Lyrasoft\Warder\Authentication\Method\WarderMethod;
use Lyrasoft\Warder\Handler\UserHandler;
use Windwalker\Authentication\Authentication;
use Windwalker\Core\User\User;
use Windwalker\Event\Event;
use Windwalker\Ioc;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\WarderPackage;

/**
 * The UserListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserListener
{
	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected $warder;

	/**
	 * UserListener constructor.
	 *
	 * @param WarderPackage $warder
	 */
	public function __construct(WarderPackage $warder = null)
	{
		$this->warder = $warder ? : WarderHelper::getPackage();
	}

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
			$container = $this->warder->getContainer();

			$session = $container->get('session');

			$uri = $container->get('uri');

			setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 100, $session->getOption('cookie_path', $uri->path), $session->getOption('cookie_domain'));
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
		$auth = $event['auth'];

		$methods = $this->warder->get('methods', array());

		foreach ($methods as $name => $class)
		{
			if (!class_exists($class))
			{
				throw new \LogicException('Class: ' . $class . ' not exists.');
			}

			if (!is_subclass_of($class, 'Windwalker\Authentication\Method\MethodInterface'))
			{
				throw new \LogicException('Class: ' . $class . ' must be sub class of Windwalker\Authentication\Method\MethodInterface');
			}

			$auth->addMethod($name, new $class($this->warder));
		}
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
		if ($this->warder->app->isConsole())
		{
			return;
		}

		$class = $this->warder->get('class.handler', 'Lyrasoft\Warder\Handler\UserHandler');

		User::setHandler(new $class($this->warder));
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
		$user = $event['user'];

		if ($user->blocked)
		{
			$event['result'] = false;

			return;
		}

		if ($user->activation)
		{
			$event['result'] = false;

			return;
		}
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
		$data = $event['data'];

		if (!$data->user)
		{
			$data->user = User::get();
		}
	}
}
