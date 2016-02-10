<?php
/**
 * Part of virtualset project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Data;

use Windwalker\Sentry\Helper\UserGroup;

/**
 * The UserData class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserData extends \Windwalker\Core\Authentication\UserData
{
	/**
	 * isAdmin
	 *
	 * @return  boolean
	 */
	public function isAdmin()
	{
		return $this->admin >= UserGroup::ADMIN;
	}

	/**
	 * notAdmin
	 *
	 * @return  boolean
	 */
	public function notAdmin()
	{
		return !$this->isAdmin();
	}

	/**
	 * isSuperUser
	 *
	 * @return  bool
	 */
	public function isSuperUser()
	{
		return $this->admin >= UserGroup::SUPERUSER;
	}

	/**
	 * notSuperUser
	 *
	 * @return  bool
	 */
	public function notSuperUser()
	{
		return !$this->isSuperUser();
	}
}
