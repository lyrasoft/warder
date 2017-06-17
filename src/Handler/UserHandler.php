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
use Lyrasoft\Warder\Model\UserModel;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Core\Mailer\Punycode;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Core\User\UserHandlerInterface;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;

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
	 * @throws \RuntimeException
	 * @throws \UnexpectedValueException
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

			if (isset($conditions['email']))
			{
				$conditions['email'] = Punycode::toAscii($conditions['email']);
			}

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

		$class = $this->warder->get('class.data', UserData::class);
		$user = new $class((array) $user);

		if (isset($user->email))
		{
			$user->email = Punycode::toUtf8($user->email);
		}

		return $user;
	}

	/**
	 * create
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return  UserData
	 * @throws \Windwalker\Record\Exception\NoResultException
	 * @throws \UnexpectedValueException
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public function save(UserDataInterface $user)
	{
		$record = $this->getRecord();

		$user->email = Punycode::toAscii($user->email);

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
	 * @throws \UnexpectedValueException
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public function delete($conditions)
	{
		$this->getRecord()->delete($conditions);

		return true;
	}

	/**
	 * login
	 *
	 * @param UserDataInterface|UserData $user
	 *
	 * @return  boolean
	 * @throws \RuntimeException
	 */
	public function login(UserDataInterface $user)
	{
		$session = $this->warder->getCurrentPackage()->app->session;

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
		$session = $this->warder->getCurrentPackage()->app->session;

		$session->destroy();
		$session->restart();

		return true;
	}

	/**
	 * getDataMapper
	 *
	 * @return  UserRecord|Record
	 */
	protected function getRecord()
	{
		$package = $this->warder->getCurrentPackage();
		$resolver = $package->getMvcResolver()->getModelResolver();

		/** @var UserModel $model */
		$model = $resolver->create('UserModel', null, null, $package->app->database);

		return $model->getRecord();
	}
}
