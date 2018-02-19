<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Validator\UserExistsValidator;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;
use Windwalker\Validator\Rule\EmailValidator;

/**
 * The RegistrationDefinition class.
 *
 * @since  1.0
 */
class RegistrationDefinition extends AbstractFieldDefinition
{
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
        $loginName  = WarderHelper::getLoginName();
        $langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

        $form->fieldset('basic', function (Form $form) use ($loginName, $langPrefix) {
            $this->text('name')
                ->label(Translator::translate($langPrefix . 'user.field.name'))
                ->addFilter('trim')
                ->required();

            if (strtolower($loginName) !== 'email') {
                $this->text($loginName)
                    ->label(Translator::translate($langPrefix . 'user.field.' . $loginName))
                    ->addValidator(new UserExistsValidator($loginName))
                    ->addFilter('trim')
                    ->required();
            }

            $this->email('email')
                ->label(Translator::translate($langPrefix . 'user.field.email'))
                ->addValidator(new UserExistsValidator('email'))
                ->addValidator(EmailValidator::class)
                ->addFilter('trim')
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
