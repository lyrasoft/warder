<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Warder\Admin\Form\Users;

use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UsersFilterDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class FilterDefinition implements FieldDefinitionInterface
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
		$langPrefix = $warder->get('admin.language.prefix');

		/*
		 * Search Control
		 * -------------------------------------------------
		 * Add search fields as options, by default, model will search all columns.
		 * If you hop that user can choose a field to search, change "display" to true.
		 */
		$form->wrap(null, 'search', function (Form $form) use ($loginName, $langPrefix)
		{
			// Search Field
			$fieldField = new ListField;

			$fieldField->label(Translator::translate('phoenix.grid.search.field.label'))
				->set('display', false)
				->defaultValue('*')
				->addOption(new Option(Translator::translate('phoenix.core.all'), '*'));

			if ($loginName != 'email')
			{
				$fieldField->addOption(new Option(Translator::translate($langPrefix . 'field.' . $loginName), 'user.' . $loginName));
			}

			$fieldField->addOption(new Option(Translator::translate($langPrefix . 'field.name'), 'user.name'))
				->addOption(new Option(Translator::translate($langPrefix . 'field.email'), 'user.email'))
				->addOption(new Option(Translator::translate($langPrefix . 'field.id'), 'user.id'));

			$form->add('field', $fieldField);

			// Search Content
			$form->add('content', new TextField)
				->label(Translator::translate('phoenix.grid.search.label'))
				->set('placeholder', Translator::translate('phoenix.grid.search.label'));
		});

		/*
		 * Filter Control
		 * -------------------------------------------------
		 * Add filter fields to this section.
		 * Remember to add onchange event => this.form.submit(); or Phoenix.post();
		 *
		 * You can override filter actions in UsersModel::configureFilters()
		 */
		$form->wrap(null, 'filter', function(Form $form) use ($loginName, $langPrefix)
		{
			// Activated
			$form->add('activation', new ListField)
				->label($langPrefix . 'filter.activation.label')
				// Add empty option to support single deselect button
				->addOption(new Option('', ''))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.activation.select'), ''))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.activation.activated'), '1'))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.activation.unactivated'), '0'))
				->set('onchange', 'this.form.submit()');

			// State
			$form->add('user.blocked', new ListField)
				->label($langPrefix . 'filter.block.label')
				// Add empty option to support single deselect button
				->addOption(new Option('', ''))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.block.select'), ''))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.block.blocked'), '1'))
				->addOption(new Option(Translator::translate($langPrefix . 'filter.block.unblocked'), '0'))
				->set('onchange', 'this.form.submit()');
		});
	}
}
