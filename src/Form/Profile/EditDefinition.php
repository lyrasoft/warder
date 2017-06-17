<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\Profile;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Validator\UserExistsValidator;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Validator\Rule\EmailValidator;

/**
 * The EditDefinition class.
 *
 * @since  1.0
 */
class EditDefinition extends AbstractFieldDefinition
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
	public function __construct(AbstractPackage $package = null)
	{
		$this->package = $package ? : WarderHelper::getPackage();
	}

	/**
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 * @throws \InvalidArgumentException
	 */
	public function doDefine(Form $form)
	{
		$loginName = $this->package->getLoginName();

		$form->fieldset('basic', function(Form $form) use ($loginName)
		{
			$this->text('name')
				->label(Translator::translate('warder.user.field.name'))
				->addFilter('trim')
				->required();

			if (strtolower($loginName) !== 'email')
			{
				$this->text($loginName)
					->label(Translator::translate('warder.user.field.' . $loginName))
					->addFilter('trim')
					->required();
			}

			$this->email('email')
				->label(Translator::translate('warder.user.field.email'))
				->addFilter('trim')
				->addValidator(EmailValidator::class)
				->addClass('validate-email')
				->required();

			$this->password('password')
				->label(Translator::translate('warder.user.field.password'))
				->autocomplete('false');

			$this->password('password2')
				->label(Translator::translate('warder.user.field.password.confirm'))
				->autocomplete('false');
		});
	}
}
