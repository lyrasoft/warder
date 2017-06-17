<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Lyrasoft\Warder\Handler;

use Lyrasoft\Warder\Admin\Record\UserRecord;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Core\Ioc;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Core\User\UserHandlerInterface;
use Windwalker\Record\Exception\NoResultException;

/**
 * The UserHandler class.
 * 
 * @since  2.1.1
 */
class UserHandler implements UserHandlerInterface
{
	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected $warder;

	/**
	 * UserHandler constructor.
	 *
	 * @param WarderPackage $package
	 */
	public function __construct(WarderPackage $package)
	{
		$this->warder = $package;
	}

	/**
	 * load
	 *
	 * @param array $conditions
	 *
	 * @return  UserDataInterface
	 */
	public function load($conditions)
	{
		if (is_object($conditions))
		{
			$conditions = get_object_vars($conditions);
		}

		if (!$conditions)
		{
			$session = $this->warder->getContainer()->get('session');

			$user = $session->get($this->warder->get('user.session_name', 'user'));
		}
		else
		{
			$user = $this->getRecord();

			try
			{
				$user->load($conditions);

				$user = $user->dump(true);
			}
			catch (NoResultException $e)
			{
				$user = [];
			}
		}

		$class = $this->warder->get('class.data', '\Lyrasoft\Warder\Data\UserData');
		$user = new $class((array) $user);

		return $user;
	}

	/**
	 * create
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return  UserData
	 */
	public function save(UserDataInterface $user)
	{
		$record = $this->getRecord();

		if ($user->id)
		{
			$record->load($user->id)
				->bind($user->dump())
				->check()
				->store(true);
		}
		else
		{
			$record->bind($user->dump())
				->check()
				->store(true);
		}

		$user->id = $record->id;

		return $user;
	}

	/**
	 * delete
	 *
	 * @param array $conditions
	 *
	 * @return  boolean
	 */
	public function delete($conditions)
	{
		return $this->getRecord()->delete($conditions);
	}

	/**
	 * login
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return  boolean
	 */
	public function login(UserDataInterface $user)
	{
		$session = Ioc::getSession();

		unset($user->password);

		$session->set($this->warder->get('user.session_name', 'user'), $user->dump(true));

		return true;
	}

	/**
	 * logout
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return bool
	 */
	public function logout(UserDataInterface $user = null)
	{
		$session = Ioc::getSession();

		$session->destroy();
		$session->restart();

		return true;
	}

	/**
	 * getDataMapper
	 *
	 * @return  UserRecord
	 */
	protected function getRecord()
	{
		return new UserRecord;
	}
}
