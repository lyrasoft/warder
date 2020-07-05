<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Repository;

use Lyrasoft\Warder\Admin\Record\Traits\UserDataTrait;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\User\User;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Data\DataInterface;

/**
 * The LoginRepository class.
 *
 * @since  1.0
 */
class UserRepository extends \Lyrasoft\Warder\Admin\Repository\UserRepository
{
    /**
     * register
     *
     * @param DataInterface|UserDataTrait $user
     *
     * @return  DataInterface|UserDataTrait
     *
     * @throws \Exception
     */
    public function register(DataInterface $user)
    {
        if ($user->password) {
            $user->password = Warder::hashPassword($user->password);
        }

        $this->prepareDefaultData($user);

        $user->_isNew = true;

        $user->id = User::save($user)->id;

        return $user;
    }

    /**
     * prepareDefaultData
     *
     * @param DataInterface|UserDataTrait $user
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareDefaultData(DataInterface $user)
    {
        parent::prepareDefaultData($user);

        $user->blocked = $user->id === null ? 1 : $user->blocked;

        if ($user->_isNew) {
            $user->group = $user->group ?: Warder::getWarderPackage()->get('user.default_group', 'member');
        }
    }
}
