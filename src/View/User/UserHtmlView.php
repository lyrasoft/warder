<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\View\User;

use Phoenix\View\AbstractPhoenixHtmView;
use Windwalker\Core\Language\Translator;
use Windwalker\Data\Data;
use Windwalker\Warder\Form\User\ForgetConfirmDefinition;
use Windwalker\Warder\Form\User\ForgetRequestDefinition;
use Windwalker\Warder\Form\User\LoginDefinition;
use Windwalker\Warder\Form\User\RegistrationDefinition;
use Windwalker\Warder\Form\User\ResetDefinition;
use Windwalker\String\StringNormalise;
use Windwalker\Warder\Helper\WarderHelper;

/**
 * The UserHtmlView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserHtmlView extends AbstractPhoenixHtmView
{
	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		$layout = $this->getLayout();

		$method = StringNormalise::toCamelCase(str_replace('.', '_', $layout));

		if (is_callable(array($this, $method)))
		{
			$this->$method($data);
		}

		$this->setTitle();
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

	/**
	 * login
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function login(Data $data)
	{
		$data->form = $this->model->getForm(new LoginDefinition(WarderHelper::getPackage()), 'user');
	}

	/**
	 * registration
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function registration(Data $data)
	{
		$data->form = $this->model->getForm(new RegistrationDefinition(WarderHelper::getPackage()), 'user', true);
		$data->fieldsets = $data->form->getFieldsets();
	}

	/**
	 * forgetRequest
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function forgetRequest(Data $data)
	{
		$data->form = $this->model->getForm(new ForgetRequestDefinition);
	}

	/**
	 * forgetConfirm
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function forgetConfirm(Data $data)
	{
		$data->form = $this->model->getForm(new ForgetConfirmDefinition);

		$data->form->bind(array(
			'email' => $data->email,
			'token' => $data->token,
		));
	}

	/**
	 * forgetReset
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function forgetReset(Data $data)
	{
		$data->form = $this->model->getForm(new ResetDefinition);

		$data->form->bind(array(
			'email' => $data->email,
			'token' => $data->token,
		));
	}
}
