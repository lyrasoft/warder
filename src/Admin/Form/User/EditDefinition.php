<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Admin\Form\User;

use Phoenix\Field\CalendarField;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;
use Windwalker\Warder\Admin\Field\User\UserModalField;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UserEditDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class EditDefinition implements FieldDefinitionInterface
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
		$warder = WarderHelper::getPackage();

		$loginName = WarderHelper::getLoginName();
		$langPrefix = $warder->get('admin.language.prefix');

		// Basic fieldset
		$form->wrap('basic', null, function(Form $form) use ($loginName, $langPrefix)
		{
			// Name
			$form->add('name', new  Field\TextField)
				->label(Translator::translate($langPrefix . 'field.name'))
				->required(true);

			if ($loginName != 'email')
			{
				// Name
				$form->add($loginName, new  Field\TextField)
					->label(Translator::translate($langPrefix . 'field.' . $loginName))
					->required(true);
			}

			// Email
			$form->add('email', new  Field\EmailField)
				->label(Translator::translate($langPrefix . 'field.email'));

			// Password
			$form->add('password', new  Field\PasswordField)
				->label(Translator::translate($langPrefix . 'field.password'));

			// Password
			$form->add('password2', new  Field\PasswordField)
				->label(Translator::translate($langPrefix . 'field.password.confirm'));
		});

		// Created fieldset
		$form->wrap('created', null, function(Form $form) use ($langPrefix)
		{
			// Blocked
			$form->add('blocked', new  Field\RadioField)
				->label(Translator::translate($langPrefix . 'field.name'))
				->set('class', 'btn-group')
				->set('default', 0)
				->addOption(new Option(Translator::translate($langPrefix . 'field.blocked.block'), 1))
				->addOption(new Option(Translator::translate($langPrefix . 'field.blocked.unblock'), 0));

			// ID
			$form->add('id', new Field\TextField)
				->label(Translator::translate($langPrefix . 'field.id'))
				->readonly();

			// Registered
			$form->add('registered', new CalendarField)
				->label(Translator::translate($langPrefix . 'field.registered'));

			// Last Login
			$form->add('last_login', new CalendarField)
				->label(Translator::translate($langPrefix . 'field.last.login'))
				->set('readonly', true);

			// Last Reset
			$form->add('last_reset', new CalendarField)
				->label(Translator::translate($langPrefix . 'field.last.reset'))
				->set('readonly', true);
		});
	}
}
