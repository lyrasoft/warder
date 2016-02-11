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

/**
 * The SentryHelper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class WarderHelper
{
	/**
	 * getPackage
	 *
	 * @return  \Windwalker\Core\Package\AbstractPackage
	 */
	public static function getPackage()
	{
		return PackageHelper::getPackage(
			PackageHelper::getAlias('Windwalker\Warder\WarderPackage')
		);
	}

	/**
	 * getFrontendRouting
	 *
	 * @return  array
	 */
	public static function getFrontendRouting()
	{
		return Yaml::parse(static::getPackage()->getDir() . '/routing.yml');
	}
}
