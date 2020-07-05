<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Profile;

use Lyrasoft\Unidev\Field\SingleImageDragField;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\User\User;
use Windwalker\Core\User\UserData;
use Windwalker\Data\DataInterface;
use Windwalker\Validator\Rule\EmailValidator;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class SaveController extends AbstractSaveController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'profile';

    /**
     * Property itemName.
     *
     * @var  string
     */
    protected $itemName = 'profile';

    /**
     * Property listName.
     *
     * @var  string
     */
    protected $listName = 'profile';

    /**
     * Property model.
     *
     * @var  UserRepository
     */
    protected $repository = 'user';

    /**
     * Property useTransaction.
     *
     * @var  bool
     */
    protected $useTransaction = false;

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
    protected $langPrefix = 'warder.profile.';

    /**
     * Property user.
     *
     * @var  UserData
     */
    protected $user;

    /**
     * Property guards.
     *
     * @var  array
     */
    protected $guards = [
        'group',
        'blocked',
        'activation',
        'receive_mail',
        'reset_token',
        'last_reset',
        'last_login',
        'registered',
        'modified',
    ];

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        if (!Warder::isLogin()) {
            Warder::goToLogin();
        }

        parent::prepareExecute();
    }

    /**
     * preSave
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function preSave(DataInterface $data)
    {
        $this->user = User::get();

        $data->id = $this->user->id;

        // Remove password so that session will not store this data
        unset($this->data['password'], $this->data['password2'], $data->avatar);
    }

    /**
     * postSave
     *
     * @param DataInterface $data
     *
     * @return  void
     * @throws \ReflectionException
     */
    protected function postSave(DataInterface $data)
    {
        parent::postSave($data);

        // Remove password to prevent double hash
        unset($data->password);

        // Image
        if (false !== SingleImageDragField::uploadFromController($this, 'avatar', $data,
                AvatarUploadHelper::getPath($data->id))) {
            $this->repository->save($data);
        }

        // Set user data to session if is current user.
        if (User::get()->id == $data->id) {
            User::makeUserLoggedIn(User::get($data->id));
        }
    }

    /**
     * validate
     *
     * @param DataInterface $data
     *
     * @return  void
     *
     * @throws \Exception
     */
    protected function validate(DataInterface $data)
    {
        $validator = new EmailValidator();

        if (!$validator->validate($data->email)) {
            throw new ValidateFailException(__($this->langPrefix . 'message.email.invalid'));
        }

        parent::validate($data);

        $loginName = WarderHelper::getLoginName();

        if ($loginName !== 'email') {
            $originUser = User::get([$loginName => $data->$loginName]);

            if ($originUser->notNull() && $originUser->id !== $data->id) {
                throw new ValidateFailException(__($this->langPrefix . 'message.user.account.exists'));
            }
        }

        $user = User::get(['email' => $data->email]);

        if ($user->notNull() && $user->id != $data->id) {
            throw new ValidateFailException(__($this->langPrefix . 'message.user.email.exists'));
        }

        if ('' !== (string) $data->password) {
            if ($data->password !== $data->password2) {
                throw new ValidateFailException(__($this->langPrefix . 'message.password.not.match'));
            }

            unset($data->password2);
        } else {
            unset($data->password);
        }
    }

    /**
     * getFailRedirect
     *
     * @param DataInterface $data
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getSuccessRedirect(DataInterface $data = null)
    {
        return $this->router->route('profile_edit', $this->getRedirectQuery());
    }

    /**
     * getFailRedirect
     *
     * @param DataInterface $data
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getFailRedirect(DataInterface $data = null)
    {
        return $this->router->route('profile_edit', $this->getRedirectQuery());
    }
}
