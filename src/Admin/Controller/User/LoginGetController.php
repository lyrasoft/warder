<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Controller\User;

use Phoenix\Controller\Display\DisplayController;
use Windwalker\Warder\Helper\UserHelper;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class LoginGetController extends DisplayController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'user';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		if (UserHelper::isLogin())
		{
			$this->app->redirect($this->getHomeRedirect());

			return;
		}

		$return = $this->input->getBase64(
			WarderHelper::getPackage()->get('admin.login.return_key', 'return')
		);

		if ($return)
		{
			$this->setUserState($this->getContext('return'), $return);
		}

		parent::prepareExecute();
	}

	/**
	 * getHomeRedirect
	 *
	 * @return  string
	 */
	protected function getHomeRedirect()
	{
		return $this->router->http(WarderHelper::getPackage()->get('admin.redirect.login', 'home'));
	}
}
