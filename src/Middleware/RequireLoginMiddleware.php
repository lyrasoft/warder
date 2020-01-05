<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Middleware;

use Lyrasoft\Warder\Warder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Core\Application\Middleware\AbstractWebMiddleware;
use Windwalker\Middleware\MiddlewareInterface;
use Windwalker\Utilities\Classes\OptionAccessTrait;

/**
 * The RequireLoginMiddleware class.
 *
 * @since  1.5.2
 */
class RequireLoginMiddleware extends AbstractWebMiddleware
{
    use OptionAccessTrait;

    /**
     * RequireLoginMiddleware constructor.
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Middleware logic to be invoked.
     *
     * @param   Request                      $request  The request.
     * @param   Response                     $response The response.
     * @param   callable|MiddlewareInterface $next     The next middleware.
     *
     * @return  Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __invoke(Request $request, Response $response, $next = null)
    {
        $isLogin = false;
        $handler = $this->getOption('handler');

        // Check login handler
        if (is_callable($handler)) {
            $isLogin = $this->app->getContainer()->call($handler);
        } else {
            $isLogin = Warder::isLogin();
        }

        // After failure hook
        if (!$isLogin) {
            $loginFailure = $this->getOption('login_failure');

            if (is_callable($loginFailure)) {
                $this->app->getContainer()->call($loginFailure);
            } else {
                Warder::goToLogin($this->app->uri->full);
            }
        }

        // After success hook
        $loginSuccess = $this->getOption('login_success');

        if (is_callable($loginSuccess)) {
            $this->app->getContainer()->call($loginSuccess);
        }

        // Authorisation
        $auth = $this->getOption('authorisation');

        if (is_callable($auth)) {
            $this->app->getContainer()->call($loginSuccess);
        }

        return $next($request, $response);
    }
}
