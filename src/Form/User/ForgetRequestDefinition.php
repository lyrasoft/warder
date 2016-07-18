<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Lyrasoft\Warder\Helper\WarderHelper;

/**
 * The ForgetDefinition class.
 *
 * @since  1.0
 */
class ForgetRequestDefinition implements FieldDefinitionInterface
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
		$langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

		$form->add('email', new Field\EmailField)
			->label(Translator::translate($langPrefix . 'user.field.email'))
			->set('placeholder', Translator::translate($langPrefix . 'user.field.email'))
			->required();
	}
}
