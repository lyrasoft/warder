<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Controller\Users;

use Phoenix\Controller\Batch\AbstractCopyController;

/**
 * The CopyController class.
 *
 * @since  1.0
 */
class CopyController extends AbstractCopyController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'users';

    /**
     * Property itemName.
     *
     * @var  string
     */
    protected $itemName = 'user';

    /**
     * Property listName.
     *
     * @var  string
     */
    protected $listName = 'users';

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = 'warder.';
}
