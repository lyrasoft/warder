<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Warder\Handler;

use Windwalker\Core\Language\Translator;
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
	 * Property mapper.
	 *
	 * @var DataMapper
	 */
	protected $mapper;

	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected $package;

	/**
	 * UserHandler constructor.
	 *
	 * @param WarderPackage $package
	 */
	public function __construct(WarderPackage $package)
	{
		$this->package = $package;
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
			$session = $this->package->getContainer()->get('system.session');

			$user = $session->get($this->package->get('user.session_name', 'user'));
		}
		else
		{
			$user = $this->getDataMapper()->findOne($conditions);
		}

		$user = new UserData((array) $user);

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
		if ($user->id)
		{
			$data = $this->getDataMapper()->findOne($user->id);

			$this->check($user);

			$data->bind($user->dump(), true);

			$this->getDataMapper()->updateOne($data, 'id', true);
		}
		else
		{
			$data = new Data($user->dump());

			$this->getDataMapper()->createOne($data);
		}

		$user->id = $data->id;

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
		return $this->getDataMapper()->delete(array('id' => $user->id));
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

		$session->set($this->package->get('user.session_name', 'user'), (array) $user);

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
	 * @return  DataMapper
	 */
	protected function getDataMapper()
	{
		if (!$this->mapper)
		{
			$this->mapper = new DataMapper($this->package->get('table.users', 'users'));
		}

		return $this->mapper;
	}

	/**
	 * check
	 *
	 * @param   UserDataInterface  $user
	 *
	 * @return  void
	 */
	protected function check(UserDataInterface $user)
	{
		$mapper = $this->getDataMapper();

		$loginName = $this->package->getLoginName();

//		if (!$user->$loginName)
//		{
//			throw new \InvalidArgumentException('No login information.');
//		}

		if ($user->$loginName)
		{
			$exists = $mapper->findOne([$loginName => $user->$loginName]);

			if (!$user->id && $exists->notNull())
			{
				throw new \InvalidArgumentException(Translator::sprintf('warder.user.save.message.exists', $loginName, $user->$loginName));
			}
		}
	}
}
