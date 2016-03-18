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
			$user = $this->getDataMapper()->findOne($conditions);
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
		if ($user->id)
		{
			$data = $this->getDataMapper()->findOne($user->id);

			$this->check($user);

			$data->bind($user->dump());

			$this->getDataMapper()->updateOne($data, 'id');
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
	 * @return  DataMapper
	 */
	protected function getDataMapper()
	{
		if (!$this->mapper)
		{
			$this->mapper = new DataMapper($this->warder->get('table.users', 'users'));
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

		$loginName = $this->warder->getLoginName();

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
