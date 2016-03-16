<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Field\User;

use Windwalker\Warder\Admin\Table\Table;
use Phoenix\Field\ItemListField;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UserField class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserListField extends ItemListField
{
	/**
	 * Property ordering.
	 *
	 * @var  string
	 */
	protected $ordering = null;

	/**
	 * Property textuser.field.
	 *
	 * @var  string
	 */
	protected $textField = 'name';

	/**
	 * buildInput
	 *
	 * @param array $attrs
	 *
	 * @return  mixed|void
	 */
	public function buildInput($attrs)
	{
		$warder = WarderHelper::getPackage();
		$this->table = $warder->get('table.users', 'username');

		return parent::buildInput($attrs);
	}
}
