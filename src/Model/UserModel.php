<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Model;

use Phoenix\Model\CrudModel;
use Windwalker\Authentication\Credential;
use Windwalker\Core\Authentication\User;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;
use Windwalker\Warder\Helper\UserHelper;

/**
 * The LoginModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserModel extends \Windwalker\Warder\Admin\Model\UserModel
{
	/**
	 * register
	 *
	 * @param Data $user
	 *
	 * @return  bool
	 *
	 * @throws \Exception
	 */
	public function register(Data $user)
	{
		$user->password = UserHelper::hashPassword($user->password);

		$this->prepareDefaultData($user);

		User::save($user);

		return true;
	}

	/**
	 * prepareDefaultData
	 *
	 * @param Data $user
	 *
	 * @return  void
	 */
	protected function prepareDefaultData(Data $user)
	{
		$user->registered = DateTime::create()->format(DateTime::FORMAT_SQL);
		$user->blocked = 1;
	}
}
