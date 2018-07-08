<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Data;

/**
 * Interface WarderUserDataInterface
 *
 * @since  1.4.2
 */
interface WarderUserDataInterface
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
}
