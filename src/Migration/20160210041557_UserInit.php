<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Core\Migration\Schema;
use Windwalker\Database\Schema\Column;
use Windwalker\Database\Schema\DataType;

/**
 * Migration class, version: 20160210041557
 */
class UserInit extends AbstractMigration
{
	/**
	 * Migrate Up.
	 */
	public function up()
	{
		$this->getTable('users', function (Schema $sc)
		{
			$sc->addColumn('id',         new Column\Primary)->comment('Primary Key');
			$sc->addColumn('name',       new Column\Varchar)->comment('Full Name');
			$sc->addColumn('username',   new Column\Varchar)->comment('Login name');
			$sc->addColumn('email',      new Column\Varchar)->comment('Email');
			$sc->addColumn('password',   new Column\Varchar)->comment('Password');
			$sc->addColumn('group',      new Column\Varchar)->comment('Group');
			$sc->addColumn('blocked',    new Column\Tinyint)->comment('0: normal, 1: blocked');
			$sc->addColumn('activation', new Column\Varchar)->comment('Activation code.');
			$sc->addColumn('reset_token', new Column\Varchar)->comment('Reset Token');
			$sc->addColumn('last_reset', new Column\Datetime)->comment('Last Reset Time');
			$sc->addColumn('last_login', new Column\Datetime)->comment('Last Login Time');
			$sc->addColumn('registered', new Column\Datetime)->comment('Register Time');
			$sc->addColumn('modified',   new Column\Datetime)->comment('Modified Time');
			$sc->addColumn('params',     new Column\Varchar)->comment('Params');
		})->create(true);
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$this->drop('users');
	}
}
