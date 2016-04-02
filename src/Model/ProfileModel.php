<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Model;

use Windwalker\Core\Authentication\User;
use Windwalker\Utilities\ArrayHelper;

/**
 * The ProfileModel class.
 *
 * @since  {DEPLOY_VERSION}
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
	public function getDefaultData()
	{
		$sessionData = (array) $this['form.data'];

		$item = User::get();

		if (ArrayHelper::getValue($sessionData, 'id') == $item->id)
		{
			unset($sessionData['password']);
			unset($sessionData['password2']);

			return $sessionData;
		}

		unset($item->password);

		return $item->dump();
	}
}
