<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\Profile;

use Lyrasoft\Unidev\Field\SingleImageDragField;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
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
        $this->package = $package ?: WarderHelper::getPackage();
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

        $warder     = WarderHelper::getPackage();
        $langPrefix = $warder->get('admin.language.prefix', 'warder.');

        $form->fieldset('basic', function (Form $form) use ($loginName, $langPrefix) {
            if (class_exists(SingleImageDragField::class)) {
                // Avatar
                $this->add('avatar', new SingleImageDragField())
                    ->label(__($langPrefix . 'user.field.avatar'))
                    ->set('force_v1', true)
                    ->set('default_image', AvatarUploadHelper::getDefaultImage());
            }

            $this->text('name')
                ->label(__($langPrefix . 'user.field.name'))
                ->addFilter('trim')
                ->required();

            if (strtolower($loginName) !== 'email') {
                $this->text($loginName)
                    ->label(__($langPrefix . 'user.field.' . $loginName))
                    ->addFilter('trim')
                    ->autocomplete('new-password')
                    ->required();
            }

            $this->email('email')
                ->label(__($langPrefix . 'user.field.email'))
                ->addFilter('trim')
                ->addValidator(EmailValidator::class)
                ->addClass('validate-email')
                ->autocomplete('new-password')
                ->required();

            $this->password('password')
                ->label(__($langPrefix . 'user.field.password'))
                ->autocomplete('new-password');

            $this->password('password2')
                ->label(__($langPrefix . 'user.field.password.confirm'))
                ->autocomplete('new-password');
        });
    }
}
