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
use Windwalker\Warder\Validator\UserExistsValidator;

/**
 * The RegistrationDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class RegistrationDefinition implements FieldDefinitionInterface
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

		$form->wrap('basic', null, function(Form $form) use ($loginName)
		{
			$form->add('name', new Field\TextField)
				->label('Full Name')
				->required();

			if (strtolower($loginName) != 'email')
			{
				$form->add($loginName, new Field\TextField)
					->label(Translator::translate('warder.field.' . $loginName))
					->setValidator(new UserExistsValidator($loginName))
					->required();
			}

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
