<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Model;

use Lyrasoft\Warder\Admin\Record\UserRecord;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Phoenix\Model\AdminModel;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\User\Exception\AuthenticateFailException;
use Windwalker\Core\User\User;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;

/**
 * The UserModel class.
 * 
 * @since  1.0
 */
class UserModel extends AdminModel
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'user';

	/**
	 * Property reorderConditions.
	 *
	 * @var  array
	 */
	protected $reorderConditions = [];

	/**
	 * getItem
	 *
	 * @param   mixed $pk
	 *
	 * @return  Data
	 */
	public function getItem($pk = null)
	{
		$state = $this->state;

		$pk = $pk ? : $state['load.conditions'];

		return $this->fetch('item.' . json_encode($pk), function() use ($pk, $state)
		{
			if (!$pk)
			{
				return new UserData;
			}

			$item = User::get($pk);

			$this->postGetItem($item);

			return $item;
		});
	}

	/**
	 * login
	 *
	 * @param string $account
	 * @param string $password
	 * @param bool   $remember
	 * @param array  $options
	 *
	 * @return bool
	 * @throws ValidateFailException
	 */
	public function login($account, $password, $remember = false, $options = [])
	{
		$loginName = WarderHelper::getLoginName();

		$credential = new Credential;
		$credential->$loginName = $account;
		$credential->password = $password;

		if (isset($options['provider']))
		{
			$credential->_provider = $options['provider'];
		}

		try
		{
			$result = User::login($credential, (bool) $remember, $options);
		}
		catch (AuthenticateFailException $e)
		{
			$langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');

			switch (array_values($e->getMessages())[0])
			{
				case Authentication::USER_NOT_FOUND:
					$message = Translator::translate($langPrefix . 'login.message.user.not.found');
					break;

				case Authentication::EMPTY_CREDENTIAL:
					$message = Translator::translate($langPrefix . 'login.message.empty.credential');
					break;

				case Authentication::INVALID_CREDENTIAL:
					$message = Translator::translate($langPrefix . 'login.message.invalid.credential');
					break;

				case Authentication::INVALID_PASSWORD:
					$message = Translator::translate($langPrefix . 'login.message.invalid.password');
					break;

				case Authentication::INVALID_USERNAME:
					$message = Translator::translate($langPrefix . 'login.message.invalid.username');
					break;

				default:
					$message = $e->getMessage();
			}

			throw new ValidateFailException($message);
		}

		return $result;
	}

	/**
	 * save
	 *
	 * @param DataInterface|UserRecord $user
	 *
	 * @return bool
	 * @throws ValidateFailException
	 */
	public function save(DataInterface $user)
	{
		if ('' !== (string) $user->password)
		{
			$user->password = UserHelper::hashPassword($user->password);
		}
		else
		{
			unset($user->password);
		}

		unset($user->password2);

		$this->prepareDefaultData($user);

		$user->bind(User::save($user));

		return true;
	}

	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getFormDefaultData()
	{
		$item = parent::getFormDefaultData();

		unset($item['password']);
		unset($item['password2']);

		return $item;
	}

	/**
	 * prepareDefaultData
	 *
	 * @param   DataInterface|UserRecord $user
	 *
	 * @return  void
	 */
	protected function prepareDefaultData(DataInterface $user)
	{
		if (!$user->registered)
		{
			$date = new Chronos;
			$user->registered = $date->toSql();
		}
	}
}
