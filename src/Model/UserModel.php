<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Model;

use Phoenix\Model\CrudModel;
use Windwalker\Authentication\Credential;
use Windwalker\Core\Authentication\User;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;
use Windwalker\Record\Record;
use Windwalker\Sentry\Helper\UserHelper;

/**
 * The LoginModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserModel extends CrudModel
{
	/**
	 * getRecord
	 *
	 * @param   string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null)
	{
		return new Record('users');
	}

	/**
	 * login
	 *
	 * @param string $username
	 * @param string $password
	 * @param bool   $remember
	 * @param array  $options
	 *
	 * @return bool
	 * @throws ValidFailException
	 */
	public function login($username, $password, $remember = false, $options = array())
	{
		$credential = new Credential;
		$credential->username = $username;
		$credential->password = $password;

		$result = User::login($credential, (bool) $remember, $options);

		if (!$result)
		{
			throw new ValidFailException('Login fail');
		}

		return $result;
	}

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
	}

	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getDefaultData()
	{
		$item = parent::getDefaultData();

		unset($item['password']);
		unset($item['password2']);

		return $item;
	}
}
