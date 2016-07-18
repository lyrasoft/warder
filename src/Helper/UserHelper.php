<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Helper;

use Phoenix\Uri\Uri;
use Windwalker\Core\User\User;
use Windwalker\Crypt\CryptHelper;
use Windwalker\Crypt\Password;
use Windwalker\Ioc;

/**
 * The UserHelper class.
 *
 * @since  1.0
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
		$user = User::getUser();

		return $user->isMember();
	}

	/**
	 * authorise
	 *
	 * @return  boolean
	 */
	public static function authorise()
	{
		$config = Ioc::getConfig();

		$requestLogin = $config->get('route.extra.warder.require_login', true);

		return UserHelper::isLogin() || !$requestLogin;
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
		return with(new Password)->create($password);
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
		return with(new Password)->verify($password, $hash);
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
		$query = array();

		if ($return)
		{
			$query['return'] = base64_encode($return);
		}

		$package = WarderHelper::getPackage()->getCurrentPackage();

		$url = $package->router->route('login', $query);

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
