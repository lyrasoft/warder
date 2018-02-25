<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

/**
 * The ForgetDefinition class.
 *
 * @since  1.0
 */
class ForgetConfirmDefinition extends AbstractFieldDefinition
{
    /**
     * Define the form fields.
     *
     * @param Form $form The Windwalker form object.
     *
     * @return  void
     */
    public function doDefine(Form $form)
    {
        $langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

        $this->email('email')
            ->label(Translator::translate($langPrefix . '.user.field.email'))
            ->required();

        $this->text('token')
            ->label(Translator::translate($langPrefix . 'user.field.token'))
            ->required();
    }
}
