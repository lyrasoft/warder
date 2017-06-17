<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Model;

use Windwalker\Core\User\User;

/**
 * The ProfileModel class.
 *
 * @since  1.0
 */
class ProfileModel extends UserModel
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'profile';

	/**
	 * getRecord
	 *
	 * @param string $name
	 *
	 * @return  \Windwalker\Record\Record
	 */
	public function getRecord($name = 'User')
	{
		return parent::getRecord($name);
	}

	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getFormDefaultData()
	{
		$sessionData = (array) $this['form.data'];

		$pk = $this['load.conditions'];

		$item = User::get($pk);

		if ($sessionData)
		{
			unset($sessionData['password']);
			unset($sessionData['password2']);

			return $sessionData;
		}

		unset($item->password);

		return $item->dump();
	}
}
