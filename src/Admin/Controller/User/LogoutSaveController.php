<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Controller\User;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Authentication\User;
use Windwalker\Warder\Helper\WarderHelper;

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

		$this->setRedirect($this->router->http(WarderHelper::getPackage()->get('admin.redirect.logout', 'home')));

		return true;
	}
}
