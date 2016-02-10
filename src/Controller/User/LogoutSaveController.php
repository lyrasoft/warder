<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Controller\User;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Authentication\User;

/**
 * The GetController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class LogoutSaveController extends AbstractPhoenixController
{
	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		User::logout();

		$this->setRedirect($this->router->http('home'));

		return true;
	}
}
