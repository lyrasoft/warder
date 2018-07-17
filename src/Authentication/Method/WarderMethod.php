<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Authentication\Method;

use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Warder;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Authentication\Method\AbstractMethod;
use Windwalker\Core\User\User;

/**
 * The Eng4TwMethod class.
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

        /** @var UserData $user */
        $user = User::get([$loginName => $credential->$loginName]);

        if ($user->isNull()) {
            $this->status = Authentication::USER_NOT_FOUND;

            return false;
        }

        if (!Warder::verifyPassword($credential->password, $user->password)) {
            $this->status = Authentication::INVALID_PASSWORD;

            return false;
        }

        if (Warder::needsRehash($user->password)) {
            $user->password = Warder::hashPassword($credential->password);

            User::save($user);
        }

        $credential->bind($user);

        $this->status = Authentication::SUCCESS;

        return true;
    }
}
