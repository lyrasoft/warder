<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Authentication\Method;

use Windwalker\Warder\Data\UserData;
use Windwalker\Warder\Helper\UserHelper;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Authentication\Method\AbstractMethod;
use Windwalker\Core\User\User;
use Windwalker\Warder\WarderPackage;

/**
 * The Eng4TwMethod class.
 *
 * @since  {DEPLOY_VERSION}
 */
class WarderMethod extends AbstractMethod
{
	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected $warder;

	/**
	 * WarderMethod constructor.
	 *
	 * @param WarderPackage $package
	 */
	public function __construct(WarderPackage $package)
	{
		$this->warder = $package;
	}

	/**
	 * authenticate
	 *
	 * @param Credential $credential
	 *
	 * @return  integer
	 */
	public function authenticate(Credential $credential)
	{
		$loginName = $this->warder->getLoginName();

		if (!$credential->$loginName || !$credential->password)
		{
			$this->status = Authentication::EMPTY_CREDENTIAL;

			return false;
		}

		/** @var UserData $user */
		$user = User::get(array($loginName => $credential->$loginName));

		if ($user->isNull())
		{
			$this->status = Authentication::USER_NOT_FOUND;

			return false;
		}

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
