<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\DataMapper;

use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\DataMapper\AbstractDatabaseMapperProxy;

/**
 * The UserMapper class.
 *
 * @since  1.0
 */
class UserSocialMapper extends AbstractDatabaseMapperProxy
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected static $table = WarderTable::USER_SOCIALS;
}
