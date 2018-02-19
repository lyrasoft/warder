<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;


use Lyrasoft\Warder\Admin\Record\UserRecord;
use Lyrasoft\Warder\Model\UserModel;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\DateTime\Chronos;
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
class ResetSaveController extends AbstractSaveController
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
     * @var  UserModel
     */
    protected $model;

    /**
     * Property formControl.
     *
     * @var  string
     */
    protected $formControl = 'user';

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = 'warder.forget.reset.';

    /**
     * prepareExecute
     *
     * @return  void
     */
    protected function prepareExecute()
    {
        $this->data['email']     = $this->input->getEmail('email');
        $this->data['token']     = $this->input->getString('token');
        $this->data['password']  = $this->input->getString('password');
        $this->data['password2'] = $this->input->getString('password2');
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
        if (!trim($this->data['password'])) {
            throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.password.not.entered'));
        }

        if ($this->data['password'] !== $this->data['password2']) {
            throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
        }

        /** @var UserRecord $user */
        $user = User::get(['email' => $this->data['email']]);

        if ($user->isNull()) {
            throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
        }

        if (!Hasher::verify($this->data['token'], $user->reset_token)) {
            throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.invalid.token'));
        }

        $user->password    = Hasher::create($this->data['password']);
        $user->reset_token = '';
        $user->last_reset  = Chronos::getNullDate();

        User::save($user);
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
        return $this->router->route('forget_reset', ['token' => $this->data['token'], 'email' => $this->data['email']]);
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
        return $this->router->route('forget_complete');
    }

    /**
     * postSave
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function postSave(DataInterface $data)
    {
        parent::postSave($data);
    }
}
