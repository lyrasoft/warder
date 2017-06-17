<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Model;

use Lyrasoft\Warder\Admin\Record\Traits\UserDataTrait;
use Lyrasoft\Warder\Helper\UserHelper;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\User\User;
use Windwalker\Data\DataInterface;

/**
 * The LoginModel class.
 *
 * @since  1.0
 */
class UserModel extends \Lyrasoft\Warder\Admin\Model\UserModel
{
	/**
	 * register
	 *
	 * @param DataInterface|UserDataTrait $user
	 *
	 * @return  bool
	 *
	 * @throws \Exception
	 */
	public function register(DataInterface $user)
	{
		if ($user->password)
		{
			$user->password = UserHelper::hashPassword($user->password);
		}

		$this->prepareDefaultData($user);

		$user->id = User::save($user)->id;

		return true;
	}

	/**
	 * prepareDefaultData
	 *
	 * @param DataInterface|UserDataTrait $user
	 *
	 * @return  void
	 */
	protected function prepareDefaultData(DataInterface $user)
	{
		$user->registered = $user->registered ? : DateTime::create()->format(DateTime::getSqlFormat());
		$user->blocked = $user->blocked === null ? 1 : $user->blocked;
	}
}
