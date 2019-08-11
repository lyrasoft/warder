<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Table;

use Lyrasoft\Warder\Helper\WarderHelper;

define('WARDER_TABLE_USERS', WarderHelper::getTable('users'));
define('WARDER_TABLE_USER_SOCIALS', WarderHelper::getTable('user_socials'));
define('WARDER_TABLE_GROUPS', WarderHelper::getTable('groups'));
define('WARDER_TABLE_USER_GROUP_MAPS', WarderHelper::getTable('user_group_maps'));
define('WARDER_TABLE_ACTIONS', WarderHelper::getTable('actions'));
define('WARDER_TABLE_SESSIONS', WarderHelper::getTable('sessions'));

/**
 * The WarderTable class.
 *
 * @since  1.0
 */
interface WarderTable
{
    public const USERS = WARDER_TABLE_USERS;

    public const USER_SOCIALS = WARDER_TABLE_USER_SOCIALS;

    public const GROUPS = WARDER_TABLE_GROUPS;

    public const USER_GROUP_MAPS = WARDER_TABLE_USER_GROUP_MAPS;

    public const ACTIONS = WARDER_TABLE_ACTIONS;

    public const SESSIONS = WARDER_TABLE_SESSIONS;
}
