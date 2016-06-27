<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Record;

use Windwalker\Core\Language\Translator;
use Windwalker\Database\Driver\AbstractDatabaseDriver;
use Windwalker\Event\Event;
use Windwalker\Record\Record;
use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Admin\DataMapper\UserSocialMapper;
use Lyrasoft\Warder\Admin\Record\Traits\Record\Traits\UserDataTrait;
use Lyrasoft\Warder\Helper\WarderHelper;

/**
 * The UserRecord class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserRecord extends Record
{
	use UserDataTrait;

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

	/**
	 * check
	 *
	 * @return  static
	 */
	public function check()
	{
		$loginName = WarderHelper::getLoginName();

		if (!$this->$loginName)
		{
			throw new \InvalidArgumentException(Translator::translate('warder.user.account.empty'));
		}

		$exists = UserMapper::findOne(array($loginName => $this->$loginName));

		if ($exists->notNull() && $this->id != $exists->id)
		{
			throw new \InvalidArgumentException(Translator::sprintf('warder.user.save.message.exists', $loginName, $this->$loginName));
		}

		if ($this->email)
		{
			$exists = UserMapper::findOne(array('email' => $this->email));

			if ($exists->notNull() && $this->id != $exists->id)
			{
				throw new \InvalidArgumentException(Translator::sprintf('warder.user.save.message.exists', $loginName, $this->email));
			}
		}

		return $this;
	}

	/**
	 * onAfterLoad
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onAfterLoad(Event $event)
	{
		// Add your logic
	}

	/**
	 * onAfterStore
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onAfterStore(Event $event)
	{
		// Add your logic
	}

	/**
	 * onAfterDelete
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onAfterDelete(Event $event)
	{
		UserSocialMapper::delete(array('user_id' => $this->id));
	}
}
