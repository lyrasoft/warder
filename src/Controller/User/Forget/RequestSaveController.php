<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Mailer\Mailer;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Mailer\Punycode;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\Security\Hasher;
use Windwalker\Core\User\User;
use Windwalker\Core\View\HtmlView;
use Windwalker\Data\DataInterface;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class RequestSaveController extends AbstractSaveController
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
    protected $langPrefix = 'warder.forget.request.';

    /**
     * doSave
     *
     * @param DataInterface $data
     *
     * @return  bool
     *
     * @throws ValidateFailException
     * @throws \Exception
     */
    protected function doSave(DataInterface $data)
    {
        $email = $this->input->getEmail('email');

        if (!$email) {
            throw new ValidateFailException(__($this->langPrefix . 'message.user.not.found'));
        }

        $view = $this->getView();

        $user = User::get(['email' => $email]);

        if ($user->isNull()) {
            throw new ValidateFailException(__($this->langPrefix . 'message.user.not.found'));
        }

        $token = Warder::getToken($user->email);
        $link  = $this->router->to('forget_reset', ['token' => $token, 'email' => Punycode::toAscii($email)])
            ->c('locale', false)
            ->full();

        $user->reset_token = Hasher::create($token);
        $user->last_reset  = Chronos::create()->toSql();

        User::save($user);

        $view['user']  = $user;
        $view['token'] = $token;
        $view['link']  = $link;

        $body = $this->getMailBody($view);

        $this->sendEmail($user->email, $body);

        return true;
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
        return $view->setLayout('mail.forget')->render();
    }

    /**
     * sendEmail
     *
     * @param string $email
     * @param string $body
     *
     * @return  void
     */
    protected function sendEmail($email, $body)
    {
        Mailer::send(function (MailMessage $message) use ($email, $body) {
            $message->subject(__($this->langPrefix . 'mail.subject'))
                ->from($this->app->get('mail.from.email', $this->app->get('mail.from.email')),
                    $this->app->get('mail.from.name', $this->app->get('mail.from.name')))
                ->to($email)
                ->body($body);
        });
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
        return $this->router->route('forget_request');
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
        return $this->router->route('forget_confirm');
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

    /**
     * getRecord
     *
     * @param string $name
     *
     * @return  \Windwalker\Record\Record
     */
    public function getRecord($name = 'User')
    {
        return parent::getRecord($name);
    }
}
