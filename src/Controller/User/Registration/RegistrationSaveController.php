<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Registration;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Core\View\HtmlView;
use Windwalker\Data\DataInterface;
use Windwalker\Validator\Rule\EmailValidator;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class RegistrationSaveController extends AbstractSaveController
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
    protected $langPrefix = 'warder.registration.';

    /**
     * Property useTransaction.
     *
     * @var  bool
     */
    protected $useTransaction = true;

    /**
     * Property token.
     *
     * @var  string
     */
    protected $token;

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
            $warder = WarderHelper::getPackage();

            $this->redirect($this->router->route($warder->get('frontend.redirect.login', 'home')));

            return;
        }

        parent::prepareExecute();

        $this->token = Warder::getToken($this->data['email']);

        $this->data['activation'] = Warder::hashPassword($this->token);
    }

    /**
     * preSave
     *
     * @param DataInterface $data
     *
     * @return void
     */
    protected function preSave(DataInterface $data)
    {
        // Remove password from original data to make sure password won't push to session.
        unset($this->data['password']);
        unset($this->data['password2']);
    }

    /**
     * doSave
     *
     * @param DataInterface $data
     *
     * @return  void
     * @throws \Exception
     */
    protected function doSave(DataInterface $data)
    {
        $this->prepareStore($data);

        $this->validate($data);

        $this->repository->register($data);
    }

    /**
     * postSave
     *
     * @param DataInterface $user
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function postSave(DataInterface $user)
    {
        // Mail
        $view = $this->getView();

        $view['link'] = $this->router->route('registration_activate',
            ['email' => $user->email, 'token' => $this->token], CoreRouter::TYPE_FULL);
        $view['user'] = $user;

        $body = $this->getMailBody($view);

        $this->sendEmail($user->email, $body);
    }

    /**
     * getMailBody
     *
     * @param HtmlView $view
     *
     * @return  string
     * @throws \ReflectionException
     */
    protected function getMailBody(HtmlView $view)
    {
        return $view->setLayout('mail.registration')->render();
    }

    /**
     * sendEmail
     *
     * @param string $email
     * @param string $body
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    protected function sendEmail($email, $body)
    {
        $this->app->mailer->send(function (MailMessage $message) use ($email, $body) {
            $message->subject(__($this->langPrefix . 'mail.subject'))
                ->from($this->app->get('mail.from.email', $this->app->get('mail.from.email')),
                    $this->app->get('mail.from.name', $this->app->get('mail.from.name')))
                ->to($email)
                ->body($body);
        });
    }

    /**
     * validate
     *
     * @param  DataInterface $data
     *
     * @return  void
     *
     * @throws ValidateFailException
     */
    protected function validate(DataInterface $data)
    {
        if (!(new EmailValidator)->validate($data->email)) {
            throw new ValidateFailException(__($this->langPrefix . 'message.email.invalid'));
        }

        $form = $this->repository->getForm('registration', 'user');

        $this->repository->validate($data->dump(), $form);

        if (!$data->password) {
            throw new ValidateFailException(__($this->langPrefix . 'message.password.not.entered'));
        }

        if ($data->password !== $data->password2) {
            throw new ValidateFailException(__($this->langPrefix . 'message.password.not.match'));
        }

        unset($data->password2);
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

        if ($return) {
            return base64_decode($return);
        }

        return $this->router->route('login');
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
        return $this->router->route('registration');
    }
}
