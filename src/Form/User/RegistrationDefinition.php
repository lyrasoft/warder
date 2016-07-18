<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Validator\UserExistsValidator;
use Lyrasoft\Warder\WarderPackage;

/**
 * The RegistrationDefinition class.
 *
 * @since  1.0
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
		$loginName = WarderHelper::getLoginName();
		$langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

		$form->wrap('basic', null, function(Form $form) use ($loginName, $langPrefix)
		{
			$form->add('name', new Field\TextField)
				->label(Translator::translate($langPrefix . 'user.field.name'))
				->required();

			if (strtolower($loginName) != 'email')
			{
				$form->add($loginName, new Field\TextField)
					->label(Translator::translate($langPrefix . 'user.field.' . $loginName))
					->setValidator(new UserExistsValidator($loginName))
					->required();
			}

			$form->add('email', new Field\EmailField)
				->label(Translator::translate($langPrefix . 'user.field.email'))
				->setValidator(new UserExistsValidator('email'))
				->required();

			$form->add('password', new Field\PasswordField)
				->label(Translator::translate($langPrefix . 'user.field.password'))
				->set('autocomplete', 'off');

			$form->add('password2', new Field\PasswordField)
				->label(Translator::translate($langPrefix . 'user.field.password.confirm'))
				->set('autocomplete', 'off');
		});
	}
}
