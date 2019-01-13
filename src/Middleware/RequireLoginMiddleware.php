<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Middleware;

use Lyrasoft\Warder\Warder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Core\Application\Middleware\AbstractWebMiddleware;
use Windwalker\Middleware\MiddlewareInterface;

/**
 * The RequireLoginMiddleware class.
 *
 * @since  __DEPLOY_VERSION__
 */
class RequireLoginMiddleware extends AbstractWebMiddleware
{
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
        if (!Warder::isLogin()) {
            Warder::goToLogin($this->app->uri->full);
        }

        return $next($request, $response);
    }
}
