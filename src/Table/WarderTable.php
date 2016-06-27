<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Table;

use Lyrasoft\Warder\Helper\WarderHelper;

define('WARDER_TABLE_USERS',           WarderHelper::getTable('users'));
define('WARDER_TABLE_USER_SOCIALS',    WarderHelper::getTable('user_socials'));
define('WARDER_TABLE_GROUPS',          WarderHelper::getTable('groups'));
define('WARDER_TABLE_USER_GROUP_MAPS', WarderHelper::getTable('user_group_maps'));
define('WARDER_TABLE_ACTIONS',         WarderHelper::getTable('actions'));

/**
 * The WarderTable class.
 *
 * @since  {DEPLOY_VERSION}
 */
class WarderTable
{
	const USERS = WARDER_TABLE_USERS;

	const USER_SOCIALS = WARDER_TABLE_USER_SOCIALS;

	const GROUPS = WARDER_TABLE_GROUPS;

	const USER_GROUP_MAPS = WARDER_TABLE_USER_GROUP_MAPS;

	const ACTIONS = WARDER_TABLE_ACTIONS;
}
