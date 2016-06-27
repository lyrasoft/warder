<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Field\User;

use Windwalker\Core\Language\Translator;
use Windwalker\Warder\Admin\Table\Table;
use Phoenix\Field\ModalField;
use Windwalker\Warder\Helper\WarderHelper;
use Windwalker\Warder\Table\WarderTable;

/**
 * The UserModalField class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserModalField extends ModalField
{
	/**
	 * Property view.
	 *
	 * @var  string
	 */
	protected $view = 'users';

	/**
	 * Property titleuser.field.
	 *
	 * @var  string
	 */
	protected $titleField = 'name';

	/**
	 * Property keyuser.field.
	 *
	 * @var  string
	 */
	protected $keyField = 'id';

	/**
	 * buildInput
	 *
	 * @param array $attrs
	 *
	 * @return  string
	 */
	public function buildInput($attrs)
	{
		$warder = WarderHelper::getPackage();
		$this->package = $this->get('package') ? : WarderHelper::getAdminPackage(true);
		$this->table = $this->get('table') ? : WarderTable::USERS;
		$langPrefix = $this->get('lang_prefix') ? : $warder->get('admin.language.prefix');

		$this->def('buttonText', '<i class="glyphicon glyphicon-user fa fa-user"></i> ' . Translator::translate($langPrefix . 'user.modal.field.button.select'));

		return parent::buildInput($attrs);
	}
}
