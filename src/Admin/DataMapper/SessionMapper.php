<?php

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2020 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Admin\DataMapper;

use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\DataMapper\AbstractDatabaseMapperProxy;

/**
 * The SessionMapper class.
 *
 * @since  __DEPLOY_VERSION__
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
