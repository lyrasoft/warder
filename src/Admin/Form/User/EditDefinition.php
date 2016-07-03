<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Form\User;

use Lyrasoft\Luna\Field\Image\SingleImageDragField;
use Phoenix\Field\CalendarField;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;

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
		$langPrefix = $warder->get('admin.language.prefix', 'warder.');

		// Basic fieldset
		$form->wrap('basic', null, function(Form $form) use ($loginName, $langPrefix)
		{
			// Name
			$form->add('name', new  Field\TextField)
				->label(Translator::translate($langPrefix . 'user.field.name'))
				->required(true);

			if ($loginName != 'email')
			{
				// Name
				$form->add($loginName, new  Field\TextField)
					->label(Translator::translate($langPrefix . 'user.field.' . $loginName))
					->required(true);
			}

			// Email
			$form->add('email', new  Field\EmailField)
				->label(Translator::translate($langPrefix . 'user.field.email'))
				->required();

			// Password
			$form->add('password', new  Field\PasswordField)
				->label(Translator::translate($langPrefix . 'user.field.password'));

			// Password
			$form->add('password2', new  Field\PasswordField)
				->label(Translator::translate($langPrefix . 'user.field.password.confirm'));
		});

		// Created fieldset
		$form->wrap('created', null, function(Form $form) use ($langPrefix)
		{
			if (class_exists(SingleImageDragField::class))
			{
				// Avatar
				$form->add('avatar', new SingleImageDragField)
					->label(Translator::translate($langPrefix . 'user.field.avatar'))
					->set('default_image', AvatarUploadHelper::getDefaultImage());
			}

			// Blocked
			$form->add('blocked', new  Field\RadioField)
				->label(Translator::translate($langPrefix . 'user.field.name'))
				->set('class', 'btn-group')
				->set('default', 0)
				->option(Translator::translate($langPrefix . 'user.field.blocked.block'), 1)
				->option(Translator::translate($langPrefix . 'user.field.blocked.unblock'), 0);

			// ID
			$form->add('id', new Field\TextField)
				->label(Translator::translate($langPrefix . 'user.field.id'))
				->readonly();

			// Registered
			$form->add('registered', new CalendarField)
				->label(Translator::translate($langPrefix . 'user.field.registered'))
				->disabled();

			// Last Login
			$form->add('last_login', new CalendarField)
				->label(Translator::translate($langPrefix . 'user.field.last.login'))
				->disabled();

			// Last Reset
//			$form->add('last_reset', new CalendarField)
//				->label(Translator::translate($langPrefix . 'user.field.last.reset'))
//				->set('readonly', true);
		});
	}
}
