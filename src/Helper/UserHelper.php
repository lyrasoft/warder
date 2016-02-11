<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Helper;

use Admin\AdminPackage;
use Phoenix\Uri\Uri;
use Windwalker\Core\Authentication\User;
use Windwalker\Core\Router\Router;
use Windwalker\Crypt\CryptHelper;
use Windwalker\Crypt\Password;
use Windwalker\Ioc;

/**
 * The UserHelper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserHelper
{
	/**
	 * isLogin
	 *
	 * @return  boolean
	 */
	public static function isLogin()
	{
		$user = User::get();

		return $user->isMember();
	}

	/**
	 * hashPassword
	 *
	 * @param   string  $password
	 *
	 * @return  string
	 */
	public static function hashPassword($password)
	{
		return (new Password)->create($password);
	}

	/**
	 * verifyPassword
	 *
	 * @param   string  $password
	 * @param   string  $hash
	 *
	 * @return  boolean
	 */
	public static function verifyPassword($password, $hash)
	{
		return (new Password)->verify($password, $hash);
	}

	/**
	 * goToLogin
	 *
	 * @param   string  $return
	 *
	 * @return  void
	 */
	public static function goToLogin($return = null)
	{
		$query = [];

		if ($return)
		{
			$query['return'] = base64_encode($return);
		}

		$package = Ioc::get('current.package');

		if ($package instanceof AdminPackage)
		{
			$route = 'admin:login';
		}
		else
		{
			$route = 'frontend:login';
		}

		$url = Router::http($route, $query);

		Ioc::getApplication()->redirect($url);
	}

	/**
	 * fakeAvatar
	 *
	 * @return  string
	 */
	public static function fakeAvatar()
	{
		$gender = rand(0, 1) ? 'men' : 'women';

		$max = $gender == 'men' ? 100 : 95;

		return sprintf('https://randomuser.me/api/portraits/%s/%s.jpg', $gender, rand(0, $max));
	}

	/**
	 * defaultAvatar
	 *
	 * @return  string
	 */
	public static function defaultAvatar()
	{
		return Uri::media(Uri::RELATIVE) . 'images/users/default-avatar.gif';
	}

	/**
	 * getToken
	 *
	 * @param string $data
	 * @param string $secret
	 *
	 * @return  string
	 */
	public static function getToken($data = null, $secret = null)
	{
		$secret = $secret ? : Ioc::getConfig()->get('system.secret');

		$data = json_encode($data);

		return md5($secret . $data . uniqid() . CryptHelper::genRandomBytes());
	}
}
