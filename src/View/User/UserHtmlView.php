<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\View\User;

use Phoenix\View\AbstractPhoenixHtmView;
use Windwalker\Data\Data;
use Windwalker\Sentry\Form\User\ForgetConfirmDefinition;
use Windwalker\Sentry\Form\User\ForgetRequestDefinition;
use Windwalker\Sentry\Form\User\LoginDefinition;
use Windwalker\Sentry\Form\User\RegistrationDefinition;
use Windwalker\Sentry\Form\User\ResetDefinition;
use Windwalker\String\StringNormalise;

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
		$data->form = $this->model->getForm(new LoginDefinition, 'user');
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
		$data->form = $this->model->getForm(new RegistrationDefinition, 'user', true);
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
