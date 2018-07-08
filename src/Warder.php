<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder;

use Lyrasoft\Warder\Data\WarderUserDataInterface;
use Windwalker\Core\User\User;

/**
 * The Warder class.
 *
 * @method static WarderUserDataInterface getUser($conditions = [])
 * @method static WarderUserDataInterface get($conditions = [])
 * @method static WarderUserDataInterface save($user = [], $options = [])
 *
 * @since  __DEPLOY_VERSION__
 */
class Warder extends User
{
}
