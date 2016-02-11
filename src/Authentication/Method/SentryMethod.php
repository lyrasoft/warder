<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Authentication\Method;

use Admin\AdminPackage;
use Windwalker\Warder\Data\UserData;
use Windwalker\Warder\Helper\UserHelper;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Authentication\Method\AbstractMethod;
use Windwalker\Core\Authentication\User;
use Windwalker\Ioc;

/**
 * The Eng4TwMethod class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SentryMethod extends AbstractMethod
{
	/**
	 * authenticate
	 *
	 * @param Credential $credential
	 *
	 * @return  integer
	 */
	public function authenticate(Credential $credential)
	{
		if (!$credential->username || !$credential->password)
		{
			$this->status = Authentication::EMPTY_CREDENTIAL;

			return false;
		}

		/** @var UserData $user */
		$user = User::get(array('username' => $credential->username));

		if ($user->isNull())
		{
			$this->status = Authentication::USER_NOT_FOUND;

			return false;
		}

		// Check is admin in admin-area
//		if (Ioc::get('current.package') instanceof AdminPackage && $user->notAdmin())
//		{
//			$this->status = Authentication::USER_NOT_FOUND;
//
//			return false;
//		}

		if (!UserHelper::verifyPassword($credential->password, $user->password))
		{
			$this->status = Authentication::INVALID_CREDENTIAL;

			return false;
		}

		$credential->bind($user);

		$this->status = Authentication::SUCCESS;

		return true;
	}
}
