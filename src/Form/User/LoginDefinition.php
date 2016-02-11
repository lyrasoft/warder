<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Form\User;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The LoginDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class LoginDefinition implements FieldDefinitionInterface
{
	/**
	 * Property package.
	 *
	 * @var  AbstractPackage
	 */
	protected $package;

	/**
	 * WarderMethod constructor.
	 *
	 * @param AbstractPackage $package
	 */
	public function __construct(AbstractPackage $package)
	{
		$this->package = $package;
	}

	/**
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 */
	public function define(Form $form)
	{
		$loginName = $this->package->get('user.login_name', 'username');

		$form->add($loginName, new Field\TextField)
			->label(Translator::translate('warder.field.' . $loginName));

		$form->add('password', new Field\PasswordField)
			->label(Translator::translate('warder.field.password'));

		$form->add('remember', new Field\CheckboxField)
			->label(Translator::translate('warder.field.remember'));
	}
}
