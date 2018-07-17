<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\User\User;
use Windwalker\Data\DataInterface;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class LoginSaveController extends AbstractSaveController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'user';

    /**
     * Property model.
     *
     * @var  UserRepository
     */
    protected $repository;

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
    protected $langPrefix = 'warder.login.';

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        if (Warder::isLogin()) {
            $this->redirect($this->getSuccessRedirect());

            return;
        }

        parent::prepareExecute();
    }

    /**
     * doSave
     *
     * @param DataInterface $data
     *
     * @return void
     * @throws \Exception
     */
    protected function doSave(DataInterface $data)
    {
        $loginName = WarderHelper::getLoginName();

        $this->repository->login($data->$loginName, $data->password, $data->remember, []);

        $user = User::get();
        $keyName = $this->repository->getKeyName();

        $this->repository->getDataMapper()->updateBatch(
            ['last_login' => Chronos::create()->toSql()],
            [$keyName => $user->$keyName]
        );
    }

    /**
     * getSuccessRedirect
     *
     * @param DataInterface $data
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getSuccessRedirect(DataInterface $data = null)
    {
        $return = $this->input->getBase64('return');

        if (!$return) {
            $return = $this->getUserState($this->getContext('return'));
        }

        if ($return) {
            $this->removeUserState($this->getContext('return'));

            return base64_decode($return);
        } else {
            return $this->router->route(WarderHelper::getPackage()->get('frontend.redirect.login', 'home'));
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
    protected function getFailRedirect(DataInterface $data = null)
    {
        return $this->router->route('login', $this->getRedirectQuery());
    }
}
