<?php
/**
 * Part of Warder project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Data;

use Windwalker\Core\User\User;

/**
 * The UserData class.
 *
 * @since  1.0
 */
class UserData extends \Windwalker\Core\User\UserData implements WarderUserDataInterface
{
    /**
     * authorise
     *
     * @param string $policy
     * @param mixed  ...$args
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function authorise($policy, ...$args)
    {
        return User::authorise($policy, $this, ...$args);
    }

    /**
     * can
     *
     * @param string $policy
     * @param mixed  ...$args
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function can($policy, ...$args)
    {
        return $this->authorise($policy, $this, ...$args);
    }

    /**
     * cannot
     *
     * @param string $policy
     * @param mixed  ...$args
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function cannot($policy, ...$args)
    {
        return !$this->can($policy, $this, ...$args);
    }
}
