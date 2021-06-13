<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\DataMapper;

use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\Legacy\DataMapper\AbstractDatabaseMapperProxy;

/**
 * The UserMapper class.
 *
 * @since  1.0
 */
class UserMapper extends AbstractDatabaseMapperProxy
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected static $table = WarderTable::USERS;

    /**
     * Keep plural fo B/C
     *
     * @var  string
     */
    protected static $alias = 'users';
}
