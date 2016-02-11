<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder;

use Windwalker\Core\Package\PackageHelper;
use Windwalker\Warder\Listener\SentryListener;
use Windwalker\Warder\Listener\UserListener;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Event\Dispatcher;

define('SENTRY_ROOT', dirname(__DIR__));
define('SENTRY_SOURCE', SENTRY_ROOT . '/src');
define('SENTRY_TEMPLATES', SENTRY_ROOT . '/templates');

/**
 * The UserPackage class.
 *
 * @since  {DEPLOY_VERSION}
 */
class WarderPackage extends AbstractPackage
{
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

		$dispatcher->addListener(new UserListener)
			->addListener(new SentryListener);
	}

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$package = PackageHelper::getPackage('animal');

		$controller = $this->currentController;

		$controller->setPackage($package);

//		$view = $controller->getView();
//
//		if ($view instanceof PhpHtmlView)
//		{
//			$this->container->share('view.user.html', function () use ($controller)
//			{
//				$view = new UserHtmlView;
//
//				$view->setConfig($controller->getConfig());
//
//				return $view;
//			})->alias('view.user.html', 'view.user.html');
//		}
//
//		$model = $controller->getModel();
//
//		if ($model instanceof Model)
//		{
//			$this->container->share('model.user', function () use ($controller)
//			{
//				$model = new UserModel;
//
//				$model->setConfig($controller->getConfig());
//
//				return $model;
//			})->alias('model.user', 'model.user');
//		}
	}
}
