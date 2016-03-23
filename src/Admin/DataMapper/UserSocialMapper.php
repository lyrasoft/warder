<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\DataMapper;

use Windwalker\Core\DataMapper\AbstractDataMapperProxy;
use Windwalker\Warder\Table\WarderTable;

/**
 * The UserMapper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserSocialMapper extends AbstractDataMapperProxy
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected static $table = WarderTable::USER_SOCIALS;
}
