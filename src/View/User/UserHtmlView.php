<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\View\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Phoenix\View\AbstractPhoenixHtmView;
use Windwalker\Core\Language\Translator;
use Windwalker\Data\DataInterface;
use Windwalker\String\StringNormalise;

/**
 * The UserHtmlView class.
 *
 * @since  1.0
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

        if (is_callable([$this, $method])) {
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

        if ($layout !== 'user' && !$title) {
            $langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');
            $title      = Translator::translate($langPrefix . $layout . '.title');
        }

        return parent::setTitle($title);
    }

    /**
     * login
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function login(DataInterface $data)
    {
        $data->form = $this->repository->getForm('login', 'user');
    }

    /**
     * registration
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function registration(DataInterface $data)
    {
        $data->form      = $this->model->getForm('registration', 'user', true);
        $data->fieldsets = $data->form->getFieldsets();
    }

    /**
     * forgetRequest
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function forgetRequest(DataInterface $data)
    {
        $data->form = $this->model->getForm('ForgetRequest');
    }

    /**
     * forgetConfirm
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function forgetConfirm(DataInterface $data)
    {
        $data->form = $this->model->getForm('ForgetConfirm');

        $data->form->bind(
            [
                'email' => $data->email,
                'token' => $data->token,
            ]
        );
    }

    /**
     * forgetReset
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function forgetReset(DataInterface $data)
    {
        $data->form = $this->model->getForm('Reset');

        $data->form->bind(
            [
                'email' => $data->email,
                'token' => $data->token,
            ]
        );
    }
}
