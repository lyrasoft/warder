<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Form\User;

use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The ForgetDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ResetDefinition implements FieldDefinitionInterface
{
	/**
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 */
	public function define(Form $form)
	{
		$form->add('password', new Field\PasswordField)
			->label(Translator::translate('warder.field.password'));

		$form->add('password2', new Field\PasswordField)
			->label(Translator::translate('warder.field.password.confirm'));

		$form->add('email', new Field\HiddenField);
		$form->add('token', new Field\HiddenField);
	}
}
