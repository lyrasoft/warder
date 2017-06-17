<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\View\Users;

use Lyrasoft\Warder\Helper\WarderHelper;
use Phoenix\Html\State\IconButton;
use Phoenix\Script\BootstrapScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\View\GridView;
use Windwalker\Core\Renderer\RendererHelper;

/**
 * The UsersHtmlView class.
 * 
 * @since  1.0
 */
class UsersHtmlView extends GridView
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'users';

	/**
	 * Property renderer.
	 *
	 * @var  string
	 */
	protected $renderer = RendererHelper::EDGE;

	/**
	 * The fields mapper.
	 *
	 * @var  array
	 */
	protected $fields = [
		'pk'          => 'id',
		'title'       => 'title',
		'alias'       => 'alias',
		'state'       => 'state',
		'ordering'    => 'ordering',
		'author'      => 'created_by',
		'author_name' => 'user_name',
		'created'     => 'created',
		'language'    => 'language',
		'lang_title'  => 'lang_title'
	];

	/**
	 * The grid config.
	 *
	 * @var  array
	 */
	protected $gridConfig = [
		'order_column' => 'user.ordering'
	];

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.';

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		parent::prepareData($data);

		$langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');

		$data->blockedButton = IconButton::create()
			->addState(
				1,
				'block',
				'ok fa fa-check text-success',
				$langPrefix . 'button.enabled.desc'
			)
			->addState(
				0,
				'unblock',
				'remove fa fa-remove text-danger',
				$langPrefix . 'button.disabled.desc'
			);

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
		PhoenixScript::grid();
		PhoenixScript::chosen();
		PhoenixScript::multiSelect('#admin-form table', ['duration' => 100]);
		BootstrapScript::checkbox(BootstrapScript::GLYPHICONS);
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
		return parent::setTitle($title);
	}
}
