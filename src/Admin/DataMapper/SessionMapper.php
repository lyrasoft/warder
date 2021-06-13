<?php

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2020 .
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Admin\DataMapper;

use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\Legacy\DataMapper\AbstractDatabaseMapperProxy;

/**
 * The SessionMapper class.
 *
 * @since  1.7.15
 */
class SessionMapper extends AbstractDatabaseMapperProxy
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected static $table = WarderTable::SESSIONS;

    /**
     * Keep plural fo B/C
     *
     * @var  string
     */
    protected static $alias = 'session';
}
