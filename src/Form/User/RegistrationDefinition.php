<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Lyrasoft\Unidev\Field\UnidevFieldTrait;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Validator\UserExistsValidator;
use Windwalker\Legacy\Core\Form\AbstractFieldDefinition;
use Windwalker\Legacy\Form\Form;
use Windwalker\Legacy\Validator\Rule\EmailValidator;

/**
 * The RegistrationDefinition class.
 *
 * @since  1.0
 */
class RegistrationDefinition extends AbstractFieldDefinition
{
    use UnidevFieldTrait;

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
                ->label(__($langPrefix . 'user.field.name'))
                ->addFilter('trim')
                ->required();

            if (strtolower($loginName) !== 'email') {
                $this->text($loginName)
                    ->label(__($langPrefix . 'user.field.' . $loginName))
                    ->addValidator(new UserExistsValidator($loginName))
                    ->addFilter('trim')
                    ->required();
            }

            $this->email('email')
                ->label(__($langPrefix . 'user.field.email'))
                ->addValidator(new UserExistsValidator('email'))
                ->addValidator(EmailValidator::class)
                ->addFilter('trim')
                ->addClass('validate-email')
                ->required();

            $this->password('password')
                ->label(__($langPrefix . 'user.field.password'))
                ->required(true)
                ->attr('data-role', 'password')
                ->autocomplete('false');

            $this->password('password2')
                ->label(__($langPrefix . 'user.field.password.confirm'))
                ->required(true)
                ->attr('data-validate', 'password-confirm')
                ->attr('data-confirm-target', '[data-role=password]')
                ->attr('data-custom-error-message', __('warder.forget.reset.message.password.not.match'))
                ->autocomplete('false');

            $this->captcha('captcha')
                ->jsVerify(true)
                ->required(true)
                ->autoValidate(true);
        });
    }
}
