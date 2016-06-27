<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\View\Profile;

use Phoenix\View\EditView;
use Windwalker\Core\Language\Translator;
use Lyrasoft\Warder\Helper\WarderHelper;

/**
 * The ProfileHtmlView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ProfileHtmlView extends EditView
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'profile';

	/**
	 * Property formControl.
	 *
	 * @var  string
	 */
	protected $formControl = 'user';

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		$data->fieldsets = $data->form->getFieldsets();
	}

	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		$layout = $this->getLayout();

		if ($layout != 'user' && !$title)
		{
			$langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');

			if ($layout == 'edit')
			{
				$layout = 'profile.edit';
			}

			$title = Translator::translate($langPrefix . $layout . '.title');
		}

		return parent::setTitle($title);
	}
}
