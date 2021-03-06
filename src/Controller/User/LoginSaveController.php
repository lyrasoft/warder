<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\User\ActivationService;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\User\Exception\LoginFailException;
use Windwalker\Core\User\User;
use Windwalker\Data\DataInterface;
use Windwalker\Router\Exception\RouteNotFoundException;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class LoginSaveController extends AbstractSaveController
{
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
     * @throws \ReflectionException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function prepareExecute()
    {
        $warder = WarderHelper::getPackage();

        if (!$warder->get('frontend.allow_login', true)) {
            throw new RouteNotFoundException('Not found');
        }

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
        $options   = [];
        $provider  = $this->input->get('provider');
        $loginName = WarderHelper::getLoginName();

        if ($provider) {
            $options['provider'] = strtolower($provider);

            $data->$loginName = null;
            $data->password   = null;
            $data->remember   = true;
        }

        try {
            $this->repository->login($data->$loginName, $data->password, $data->remember, $options);
        } catch (LoginFailException $e) {
            $code = $e->getPrevious()->getCode();

            if ($code === 40101) {
                $user = Warder::getUser([$loginName => $data->$loginName]);
                $this->app->session->set(ActivationService::RE_ACTIVATE_SESSION_KEY, $user->email);
            }

            throw $e;
        }

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
        return $this->router->route('login');
    }
}
