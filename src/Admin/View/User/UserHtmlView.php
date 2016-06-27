<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\View\User;

use Phoenix\Script\BootstrapScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\View\EditView;
use Windwalker\Core\Language\Translator;
use Lyrasoft\Warder\Admin\Form\User\LoginDefinition;
use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Renderer\RendererHelper;

/**
 * The UserHtmlView class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class UserHtmlView extends EditView
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'user';

	/**
	 * Property renderer.
	 *
	 * @var  string
	 */
	protected $renderer = RendererHelper::EDGE;

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.';

	/**
	 * prepareRender
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareRender($data)
	{
		if ($this->getLayout() == 'login')
		{
			$this->formDefinition = new LoginDefinition(WarderHelper::getPackage());
			$this->formControl = 'user';
		}

		parent::prepareRender($data);
	}

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		$this->prepareScripts();
	}

	/**
	 * prepareDocument
	 *
	 * @return  void
	 */
	protected function prepareScripts()
	{
		PhoenixScript::core();
		PhoenixScript::chosen();
		PhoenixScript::formValidation();
		BootstrapScript::checkbox(BootstrapScript::GLYPHICONS);
		BootstrapScript::buttonRadio();
		BootstrapScript::tooltip();
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
			$title = Translator::translate($langPrefix . $layout . '.title');
		}

		return parent::setTitle($title);
	}
}
