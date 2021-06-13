<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Authentication\Method;

use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Warder;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Legacy\Authentication\Authentication;
use Windwalker\Legacy\Authentication\Credential;
use Windwalker\Legacy\Authentication\Method\AbstractMethod;
use Windwalker\Legacy\Core\User\User;
use Windwalker\Legacy\Core\User\UserDataInterface;

/**
 * The WarderMethod class.
 *
 * @since  1.0
 */
class WarderMethod extends AbstractMethod
{
    /**
     * Property package.
     *
     * @var  WarderPackage
     */
    protected $warder;

    /**
     * WarderMethod constructor.
     *
     * @param WarderPackage $package
     */
    public function __construct(WarderPackage $package)
    {
        $this->warder = $package;
    }

    /**
     * authenticate
     *
     * @param Credential $credential
     *
     * @return  integer
     */
    public function authenticate(Credential $credential)
    {
        $loginName = $this->warder->getLoginName();

        if (!$credential->$loginName || !$credential->password) {
            $this->status = Authentication::EMPTY_CREDENTIAL;

            return false;
        }

        $user = $this->getUser([$loginName => $credential->$loginName]);

        if ($user->isNull()) {
            $this->status = Authentication::USER_NOT_FOUND;

            return false;
        }

        if (!$this->verifyPassword($user, $credential)) {
            $this->status = Authentication::INVALID_PASSWORD;

            return false;
        }

        $this->rehash($user, $credential);

        $credential->bind($user);

        $this->status = Authentication::SUCCESS;

        return true;
    }

    /**
     * getUser
     *
     * @param mixed $conditions
     *
     * @return  UserData|UserDataInterface
     *
     * @since  1.4.6
     */
    protected function getUser($conditions)
    {
        return User::get($conditions);
    }

    /**
     * verifyPassword
     *
     * @param UserData   $user
     * @param Credential $credential
     *
     * @return  bool
     *
     * @since  1.4.6
     */
    protected function verifyPassword(UserData $user, Credential $credential)
    {
        return Warder::verifyPassword($credential->password, $user->password);
    }

    /**
     * rehash
     *
     * @param UserData   $user
     * @param Credential $credential
     *
     * @return  void
     *
     * @since  1.4.6
     */
    protected function rehash(UserData $user, Credential $credential)
    {
        if (Warder::needsRehash($user->password)) {
            $user->password = Warder::hashPassword($credential->password);

            User::save($user);
        }
    }
}
