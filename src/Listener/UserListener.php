<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Listener;

use Windwalker\Warder\Authentication\Method\WarderMethod;
use Windwalker\Warder\Handler\UserHandler;
use Windwalker\Authentication\Authentication;
use Windwalker\Core\Authentication\User;
use Windwalker\Event\Event;
use Windwalker\Ioc;
use Windwalker\Warder\Helper\WarderHelper;
use Windwalker\Warder\WarderPackage;

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

			$session = $container->get('system.session');

			$uri = $container->get('uri');

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

		$methods = $this->warder->get('methods', array());

		foreach ($methods as $class)
		{
			if (!class_exists($class))
			{
				throw new \LogicException('Class: ' . $class . ' not exists.');
			}

			if (!is_subclass_of($class, 'Windwalker\Authentication\Method\MethodInterface'))
			{
				throw new \LogicException('Class: ' . $class . ' must be sub class of Windwalker\Authentication\Method\MethodInterface');
			}

			$auth->addMethod('warder', new $class($this->warder));
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
		$class = $this->warder->get('class.handler', 'Windwalker\Warder\Handler\UserHandler');

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
