<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Phoenix\Controller\Batch\AbstractBatchController;

/**
 * The UnpublishController class.
 *
 * @since  1.0
 */
class UnblockController extends AbstractBatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'unblock';

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = array(
		'blocked' => 0
	);

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.';
}
