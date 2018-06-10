<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Form\Users;

use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

/**
 * The GridDefinition class.
 *
 * @since  1.0
 */
class GridDefinition extends AbstractFieldDefinition
{
    /**
     * Define the form fields.
     *
     * @param Form $form The Windwalker form object.
     *
     * @return  void
     */
    protected function doDefine(Form $form)
    {
        $warder = WarderHelper::getPackage();

        $loginName  = WarderHelper::getLoginName();
        $langPrefix = $warder->get('admin.language.prefix');

        /*
         * Search Control
         * -------------------------------------------------
         * Add search fields as options, by default, model will search all columns.
         * If you hop that user can choose a field to search, change "display" to true.
         */
        $this->wrap(null, 'search', function (Form $form) use ($loginName, $langPrefix) {
            // Search Field
            $fieldField = $this->list('field')
                ->label(__('phoenix.grid.search.user.field.label'))
                ->set('display', false)
                ->defaultValue('*')
                ->option(__('phoenix.core.all'), '*');

            if ($loginName !== 'email') {
                $fieldField->option(__($langPrefix . 'user.field.' . $loginName),
                    'user.' . $loginName);
            }

            $fieldField->option(__($langPrefix . 'user.field.name'), 'user.name')
                ->option(__($langPrefix . 'user.field.email'), 'user.email')
                ->option(__($langPrefix . 'user.field.id'), 'user.id');

            // Search Content
            $this->text('content')
                ->label(__('phoenix.grid.search.label'))
                ->placeholder(__('phoenix.grid.search.label'));
        });

        /*
         * Filter Control
         * -------------------------------------------------
         * Add filter fields to this section.
         * Remember to add onchange event => this.form.submit(); or Phoenix.post();
         *
         * You can override filter actions in UsersModel::configureFilters()
         */
        $this->wrap(null, 'filter', function (Form $form) use ($loginName, $langPrefix) {
            // Activated
            $this->list('activation')
                ->label($langPrefix . 'filter.activation.label')
                // Add empty option to support single deselect button
                ->option('', '')
                ->option(__($langPrefix . 'filter.activation.select'), '')
                ->option(__($langPrefix . 'filter.activation.activated'), '1')
                ->option(__($langPrefix . 'filter.activation.unactivated'), '0')
                ->onchange('this.form.submit()');

            // State
            $this->list('user.blocked')
                ->label($langPrefix . 'filter.block.label')
                // Add empty option to support single deselect button
                ->option('', '')
                ->option(__($langPrefix . 'filter.block.select'), '')
                ->option(__($langPrefix . 'filter.block.blocked'), '1')
                ->option(__($langPrefix . 'filter.block.unblocked'), '0')
                ->onchange('this.form.submit()');
        });

        /*
         * This is batch form definition.
         * -----------------------------------------------
         * Every field is a table column.
         * For example, you can add a 'category_id' field to update item category.
         */
        $this->wrap(null, 'batch', function (Form $form) {

        });
    }
}
