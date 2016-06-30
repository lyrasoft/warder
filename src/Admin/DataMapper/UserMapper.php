<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\DataMapper;

use Windwalker\Core\DataMapper\CoreDataMapper;
use Lyrasoft\Warder\Table\WarderTable;

/**
 * The UserMapper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserMapper extends CoreDataMapper
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected static $table = WarderTable::USERS;
}
