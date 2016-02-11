<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Helper;

use Symfony\Component\Yaml\Yaml;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Warder\WarderPackage;

/**
 * The SentryHelper class.
 *
 * @since  {DEPLOY_VERSION}
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
	 * getFrontendRouting
	 *
	 * @return  array
	 */
	public static function getFrontendRouting()
	{
		return Yaml::parse(static::getPackage()->getDir() . '/routing.yml');
	}

	/**
	 * getPackage
	 *
	 * @return  \Windwalker\Core\Package\AbstractPackage
	 */
	public static function getPackage()
	{
		return static::$package;
	}

	/**
	 * Method to set property package
	 *
	 * @param   WarderPackage $package
	 *
	 * @return  void
	 */
	public static function setPackage($package)
	{
		static::$package = $package;
	}
}
