<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Data;

use Lyrasoft\Warder\Warder;
use Windwalker\Core\User\UserDataInterface;

/**
 * Interface WarderUserDataInterface
 *
 * @since  1.4.2
 */
interface WarderUserDataInterface extends UserDataInterface
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
    public function authorise($policy, ...$args);

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
    public function can($policy, ...$args);

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
    public function cannot($policy, ...$args);

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
    public function is($policy, ...$args);

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
    public function not($policy, ...$args);

    /**
     * isGroup
     *
     * @param string|array $groups
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function isGroup($groups);

    /**
     * getGroupProperties
     *
     * @return  array
     *
     * @since  1.7.3
     */
    public function getGroupProperties(): array;

    /**
     * getProp
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return  mixed|null
     *
     * @since  1.7.14
     */
    public function getProp(string $name, $default = null);

    /**
     * checkGroup
     *
     * @param callable $handler
     *
     * @return  bool
     *
     * @since  1.7.3
     */
    public function checkGroup(callable $handler): bool;

    /**
     * isLogin
     *
     * @return  bool
     *
     * @since  1.4.2
     */
    public function isLogin();
}
