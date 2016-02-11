<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Controller\User;

use Phoenix\Controller\Display\DisplayController;
use Windwalker\Warder\Helper\UserHelper;

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
			$this->package->get('frontend.login.return_key', 'return')
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
		return $this->router->http($this->package->get('frontend.redirect.login', 'home'));
	}
}
