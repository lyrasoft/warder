<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Lyrasoft\Warder\Repository\UserRepository;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\Security\Hasher;
use Windwalker\Core\User\User;
use Windwalker\Data\DataInterface;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class ConfirmSaveController extends AbstractSaveController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'user';

    /**
     * Property itemName.
     *
     * @var  string
     */
    protected $itemName = 'user';

    /**
     * Property listName.
     *
     * @var  string
     */
    protected $listName = 'user';

    /**
     * Property model.
     *
     * @var  UserRepository
     */
    protected $model;

    /**
     * Property formControl.
     *
     * @var  string
     */
    protected $formControl = 'user';

    /**
     * Property useTransaction.
     *
     * @var  bool
     */
    protected $useTransaction = false;

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = 'warder.forget.confirm.';

    /**
     * prepareExecute
     *
     * @return  void
     */
    protected function prepareExecute()
    {
        $this->data['token'] = $this->input->get('token');
        $this->data['email'] = $this->input->getEmail('email');
    }

    /**
     * doSave
     *
     * @param DataInterface $data
     *
     * @return  bool
     *
     * @throws ValidateFailException
     */
    protected function doSave(DataInterface $data)
    {
        $user = User::get(['email' => $this->data['email']]);

        if ($user->isNull()) {
            throw new ValidateFailException(__($this->langPrefix . 'user.not.found'));
        }

        // Check token
        if (!Hasher::verify($this->data['token'], $user->reset_token)) {
            throw new ValidateFailException('Invalid Token');
        }

        return true;
    }

    /**
     * getFailRedirect
     *
     * @param DataInterface $data
     *
     * @return  string
     */
    protected function getFailRedirect(DataInterface $data = null)
    {
        return $this->router->route('forget_confirm', ['email' => $this->data['email']]);
    }

    /**
     * getSuccessRedirect
     *
     * @param DataInterface $data
     *
     * @return  string
     */
    protected function getSuccessRedirect(DataInterface $data = null)
    {
        return $this->router->route('forget_reset', ['token' => $this->data['token'], 'email' => $this->data['email']]);
    }
}
