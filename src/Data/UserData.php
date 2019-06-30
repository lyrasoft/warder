<?php
/**
 * Part of Warder project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Data;

use Lyrasoft\Warder\Warder;

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
        return Warder::authorise($policy, $this, ...$args);
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

    /**
     * is
     *
     * @param string $policy
     * @param mixed  ...$args
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function is($policy, ...$args)
    {
        return $this->can($policy, $this, ...$args);
    }

    /**
     * not
     *
     * @param string $policy
     * @param mixed  ...$args
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function not($policy, ...$args)
    {
        return $this->cannot($policy, $this, ...$args);
    }

    /**
     * isGroup
     *
     * @param string|array $groups
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function isGroup($groups)
    {
        $groups = (array) $groups;

        if (!property_exists($this, 'group')) {
            return false;
        }

        return in_array($this->group, $groups, true);
    }

    /**
     * getGroupProperties
     *
     * @return  array
     *
     * @since  1.7.3
     */
    public function getGroupProperties(): array
    {
        return Warder::getGroups()[$this->group] ?? [];
    }

    /**
     * checkGroup
     *
     * @param callable $handler
     *
     * @return  bool
     *
     * @since  1.7.3
     */
    public function checkGroup(callable $handler): bool
    {
        return $handler($this->getGroupProperties());
    }

    /**
     * isLogin
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function isLogin()
    {
        return $this->isMember();
    }
}
