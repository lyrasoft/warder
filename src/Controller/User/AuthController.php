<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Security\CsrfProtection;

/**
 * The AuthController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AuthController extends AbstractPhoenixController
{
	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		\Hybrid_Endpoint::process();
	}
}
