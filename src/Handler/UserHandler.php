<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Warder\Handler;

use Windwalker\Core\Language\Translator;
use Windwalker\Record\Record;
use Windwalker\Warder\Admin\Record\UserRecord;
use Windwalker\Warder\Data\UserData;
use Windwalker\Core\Authentication\UserDataInterface;
use Windwalker\Core\Authentication\UserHandlerInterface;
use Windwalker\Core\Ioc;
use Windwalker\Data\Data;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Warder\WarderPackage;

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
			$session = $this->warder->getContainer()->get('system.session');

			$user = $session->get($this->warder->get('user.session_name', 'user'));
		}
		else
		{
			$user = $this->getRecord();
			$user->load($conditions);
			$user = $user->toArray();
		}

		$class = $this->warder->get('class.data', '\Windwalker\Warder\Data\UserData');
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
				->store();
		}
		else
		{
			$record->bind($user->dump())
				->check()
				->store();
		}

		$user->id = $record->id;

		return $user;
	}

	/**
	 * delete
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return  boolean
	 */
	public function delete(UserDataInterface $user)
	{
		return $this->getRecord()->delete($user->id);
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

		$session->set($this->warder->get('user.session_name', 'user'),$user->dump());

		return true;
	}

	/**
	 * logout
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return bool
	 */
	public function logout(UserDataInterface $user)
	{
		$session = Ioc::getSession();

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
