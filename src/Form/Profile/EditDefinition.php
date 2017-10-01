<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\Profile;

use Lyrasoft\Unidev\Field\SingleImageDragField;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
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

		$warder = WarderHelper::getPackage();
		$langPrefix = $warder->get('admin.language.prefix', 'warder.');

		$form->fieldset('basic', function(Form $form) use ($loginName, $langPrefix)
		{
			if (class_exists(SingleImageDragField::class))
			{
				// Avatar
				$this->add('avatar', new SingleImageDragField)
					->label(Translator::translate($langPrefix . 'user.field.avatar'))
					->set('default_image', AvatarUploadHelper::getDefaultImage());
			}
			
			$this->text('name')
				->label(Translator::translate($langPrefix . 'user.field.name'))
				->addFilter('trim')
				->required();

			if (strtolower($loginName) !== 'email')
			{
				$this->text($loginName)
					->label(Translator::translate($langPrefix . 'user.field.' . $loginName))
					->addFilter('trim')
					->required();
			}

			$this->email('email')
				->label(Translator::translate($langPrefix . 'user.field.email'))
				->addFilter('trim')
				->addValidator(EmailValidator::class)
				->addClass('validate-email')
				->required();

			$this->password('password')
				->label(Translator::translate($langPrefix . 'user.field.password'))
				->autocomplete('false');

			$this->password('password2')
				->label(Translator::translate($langPrefix . 'user.field.password.confirm'))
				->autocomplete('false');
		});
	}
}
