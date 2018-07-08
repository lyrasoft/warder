<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder;

use Lyrasoft\Unidev\Helper\PravatarHelper;
use Lyrasoft\Warder\Data\WarderUserDataInterface;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Ioc;
use Windwalker\Core\Security\Hasher;
use Windwalker\Core\User\User;
use Windwalker\Crypt\CryptHelper;

/**
 * The Warder class.
 *
 * @method static WarderUserDataInterface getUser($conditions = [])
 * @method static WarderUserDataInterface get($conditions = [])
 * @method static WarderUserDataInterface save($user = [], $options = [])
 *
 * @since  __DEPLOY_VERSION__
 */
class Warder extends User
{
    /**
     * isLogin
     *
     * @return  boolean
     */
    public static function isLogin()
    {
        $user = static::getUser();

        return $user->isMember();
    }

    /**
     * authorise
     *
     * @return  boolean
     */
    public static function requireLogin()
    {
        $config = static::getContainer()->get('config');

        $requestLogin = $config->get('route.extra.warder.require_login');

        return $requestLogin && !static::isLogin();
    }

    /**
     * hashPassword
     *
     * @param   string $password
     *
     * @return  string
     */
    public static function hashPassword($password)
    {
        return Hasher::create($password);
    }

    /**
     * verifyPassword
     *
     * @param   string $password
     * @param   string $hash
     *
     * @return  boolean
     */
    public static function verifyPassword($password, $hash)
    {
        return Hasher::verify($password, $hash);
    }

    /**
     * goToLogin
     *
     * @param   string $return
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function goToLogin($return = null)
    {
        $query = [];

        if ($return) {
            $query['return'] = base64_encode($return);
        }

        $package = WarderHelper::getPackage()->getCurrentPackage();

        $url = $package->router->route('login', $query);

        Ioc::getApplication()->redirect($url);
    }

    /**
     * fakeAvatar
     *
     * @param int    $size
     * @param string $uniqid
     *
     * @return  string
     */
    public static function fakeAvatar($size = 300, $uniqid = null)
    {
        return PravatarHelper::unique($size, $uniqid);
    }

    /**
     * defaultAvatar
     *
     * @return  string
     */
    public static function defaultAvatar()
    {
        return AvatarUploadHelper::getDefaultImage();
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
        $secret = $secret ?: Ioc::getConfig()->get('system.secret');

        $data = json_encode($data);

        return md5($secret . $data . uniqid('Warder', true) . CryptHelper::genRandomBytes());
    }
}
