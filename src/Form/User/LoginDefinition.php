<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Form\User;

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
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 */
	public function define(Form $form)
	{
		$form->add('username', new Field\TextField)
			->label('Username');

		$form->add('password', new Field\PasswordField)
			->label('Password');

		$form->add('remember', new Field\CheckboxField)
			->label('Remember me');
	}
}
