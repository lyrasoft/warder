<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Helper;

use Lyrasoft\Unidev\Storage\AbstractStorageHelper;
use Lyrasoft\Unidev\UnidevPackage;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\PackageHelper;

/**
 * The AvatarHelper class.
 *
 * @since  1.0
 */
class AvatarUploadHelper extends AbstractStorageHelper
{
	/**
	 * Get base folder name.
	 *
	 * @return  string
	 */
	public static function getBaseFolder()
	{
		return 'images/user/';
	}

	/**
	 * Get remote uri path.
	 *
	 * @param   mixed $identify The identify of this file or item.
	 *
	 * @return  string  Identify path.
	 */
	public static function getPath($identify)
	{
		return static::getBaseFolder() . $identify . '/avatar.jpg';
	}

	/**
	 * getDefaultImage
	 *
	 * @return  string
	 */
	public static function getDefaultImage()
	{
		$alias = PackageHelper::getAlias(UnidevPackage::class);

		return Asset::root($alias . '/images/default-avatar.png');
	}
}
