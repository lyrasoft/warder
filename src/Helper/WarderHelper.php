<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Helper;

use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\WarderPackage;

/**
 * The SentryHelper class.
 *
 * @since  1.0
 */
class WarderHelper
{
	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected static $package;

	/**
	 * getLoginName
	 *
	 * @param string $default
	 *
	 * @return string
	 */
	public static function getLoginName($default = 'username')
	{
		return static::getPackage()->getLoginName($default);
	}

	/**
	 * isFrontend
	 *
	 * @param   string $name
	 *
	 * @return  boolean
	 */
	public static function isFrontend($name = null)
	{
		return static::getPackage()->isFrontend($name);
	}

	/**
	 * isAdmin
	 *
	 * @param   string $name
	 *
	 * @return  boolean
	 */
	public static function isAdmin($name = null)
	{
		return static::getPackage()->isAdmin($name);
	}

	/**
	 * getFrontendRouting
	 *
	 * @return  array
	 */
	public static function getFrontendRouting()
	{
		if (!static::$package)
		{
			throw new \LogicException('Please register warder into Windwalker first.');
		}

		return WARDER_SOURCE . '/routing.yml';
	}

	/**
	 * getFrontendRouting
	 *
	 * @return  array
	 */
	public static function getAdminRouting()
	{
		if (!static::$package)
		{
			throw new \LogicException('Please register warder into Windwalker first.');
		}

		return WARDER_SOURCE_ADMIN . '/routing.yml';
	}

	/**
	 * getPackage
	 *
	 * @return  WarderPackage
	 */
	public static function getPackage()
	{
		if (!static::$package)
		{
			throw new \LogicException('Please register warder into Windwalker first.');
		}

		return static::$package;
	}

	/**
	 * Method to set property package
	 *
	 * @param   WarderPackage $package
	 *
	 * @return  void
	 */
	public static function setPackage(WarderPackage $package)
	{
		static::$package = $package;
	}

	/**
	 * createUserData
	 *
	 * @param array $data
	 *
	 * @return  UserData
	 */
	public static function createUserData($data = [])
	{
		return static::getPackage()->createUserData($data);
	}

	/**
	 * getTable
	 *
	 * @param string $alias
	 * @param string $default
	 *
	 * @return  string
	 */
	public static function getTable($alias, $default = null)
	{
		$default = $default ? : $alias;

		return static::getPackage()->get('table.' . $alias, $default);
	}

	/**
	 * tableExists
	 *
	 * @param   string  $alias
	 *
	 * @return  boolean
	 */
	public static function tableExists($alias)
	{
		if (!static::$package)
		{
			return false;
		}

		$table = static::getTable($alias);

		return static::getPackage()->getContainer()->get('db')->getTable($table)->exists();
	}

	/**
	 * getFrontendPackage
	 *
	 * @param bool $main
	 *
	 * @return  array|string
	 */
	public static function getFrontendPackage($main = false)
	{
		$packages = (array) static::getPackage()->get('frontend.package');

		return $main ? $packages[0] : $packages;
	}

	/**
	 * getFrontendPackage
	 *
	 * @param bool $main
	 *
	 * @return  array|string
	 */
	public static function getAdminPackage($main = false)
	{
		$packages = (array) static::getPackage()->get('admin.package');

		return $main ? $packages[0] : $packages;
	}
}
