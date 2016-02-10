<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Form\User;

use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Sentry\Controller\Validator\UserExistsValidator;

/**
 * The RegistrationDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class RegistrationDefinition implements FieldDefinitionInterface
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
		$form->wrap('basic', null, function(Form $form)
		{
			$form->add('name', new Field\TextField)
				->label('Full Name')
				->required();

			$form->add('username', new Field\TextField)
				->label('Username')
				->setValidator(new UserExistsValidator('username'))
				->required();

			$form->add('email', new Field\EmailField)
				->label('Email')
				->setValidator(new UserExistsValidator('email'))
				->required();

			$form->add('password', new Field\PasswordField)
				->label('Password')
				->set('autocomplete', 'off');

			$form->add('password2', new Field\PasswordField)
				->label('Confirm Password')
				->set('autocomplete', 'off');
		});
	}
}
