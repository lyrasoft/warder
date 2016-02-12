<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\DataMapper;

use Windwalker\DataMapper\Adapter\DatabaseAdapterInterface;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UserMapper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserMapper extends DataMapper
{
	/**
	 * Constructor.
	 *
	 * @param   string                   $table Table name.
	 * @param   string|array             $pk    Primary key.
	 * @param   DatabaseAdapterInterface $db    Database adapter.
	 */
	public function __construct($table = null, $pk = 'id', DatabaseAdapterInterface $db = null)
	{
		$table = WarderHelper::getPackage()->get('table.users', 'users');

		parent::__construct($table, $pk, $db);
	}
}
