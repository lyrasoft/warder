<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Record;

use Windwalker\Database\Driver\AbstractDatabaseDriver;
use Windwalker\Record\Record;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UserRecord class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserRecord extends Record
{
	/**
	 * Object constructor to set table and key fields.  In most cases this will
	 * be overridden by child classes to explicitly set the table and key fields
	 * for a particular database table.
	 *
	 * @param   string                 $table Name of the table to model.
	 * @param   mixed                  $keys  Name of the primary key field in the table or array of field names that
	 *                                        compose the primary key.
	 * @param   AbstractDatabaseDriver $db    DatabaseDriver object.
	 *
	 * @since   2.0
	 */
	public function __construct($table = null, $keys = 'id', AbstractDatabaseDriver $db = null)
	{
		$table = WarderHelper::getPackage()->get('table.users', 'users');

		parent::__construct($table, $keys, $db);
	}
}
