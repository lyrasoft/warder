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
use Windwalker\Warder\Helper\WarderHelper;

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
		$loginName = WarderHelper::getLoginName();
		$langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

		$form->add($loginName, new Field\TextField)
			->label(Translator::translate($langPrefix . 'user.field.' . $loginName));

		$form->add('password', new Field\PasswordField)
			->label(Translator::translate($langPrefix . 'user.field.password'));

		$form->add('remember', new Field\CheckboxField)
			->label(Translator::translate($langPrefix . 'user.field.remember'));
	}
}
