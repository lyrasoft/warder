<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Listener;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\Resolver\DataMapperResolver;
use Windwalker\Core\Package\Resolver\FieldDefinitionResolver;
use Windwalker\Core\Package\Resolver\RecordResolver;
use Windwalker\Core\View\HtmlView;
use Windwalker\Data\Data;
use Windwalker\Event\Event;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Utilities\Queue\PriorityQueue;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The SentryListener class.
 *
 * @since  1.0
 */
class WarderListener
{
    /**
     * Property package.
     *
     * @var  WarderPackage
     */
    protected $warder;

    /**
     * UserListener constructor.
     *
     * @param WarderPackage $warder
     */
    public function __construct(WarderPackage $warder = null)
    {
        $this->warder = $warder ?: WarderHelper::getPackage();
    }

    /**
     * onAfterRouting
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterRouting(Event $event)
    {
        /** @var WebApplication $app */
        $app     = $event['app'];
        $package = $app->getPackage();

        // In Warder
        if ($this->warder->isEnabled()) {
            RecordResolver::addNamespace(
                ReflectionHelper::getNamespaceName($this->warder) . '/Admin/Record',
                PriorityQueue::LOW
            );
            DataMapperResolver::addNamespace(
                ReflectionHelper::getNamespaceName($this->warder) . '/Admin/DataMapper',
                PriorityQueue::LOW
            );
            FieldDefinitionResolver::addNamespace(ReflectionHelper::getNamespaceName($package) . '/Form');
        }

        // Frontend
        if ($this->warder->isFrontend()) {
            $package->getMvcResolver()
                ->addNamespace(ReflectionHelper::getNamespaceName($this->warder), PriorityQueue::BELOW_NORMAL);

            FieldDefinitionResolver::addNamespace((ReflectionHelper::getNamespaceName($this->warder) . '\Form'));
        } elseif ($this->warder->isAdmin()) {
            $package->getMvcResolver()
                ->addNamespace(
                    ReflectionHelper::getNamespaceName($this->warder) . '\Admin',
                    PriorityQueue::BELOW_NORMAL
                );

            FieldDefinitionResolver::addNamespace(ReflectionHelper::getNamespaceName($this->warder) . '\Admin\Form');
        }
    }

    /**
     * onViewBeforeRender
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onViewBeforeRender(Event $event)
    {
        $view = $event['view'];

        if (!$view instanceof HtmlView) {
            return;
        }

        /**
         * @var HtmlView      $view
         * @var BladeRenderer $renderer
         */
        $name     = $view->getName();
        $renderer = $view->getRenderer();

        $app = $view->getPackage()->app;

        // Prepare View data
        if ($this->warder->isFrontend()) {
            // Extends
            $view['warder'] = new Data([
                'extends' => $this->warder->get('frontend.view.extends', '_global.html'),
                'noauthExtends' => $this->warder->get(
                    'frontend.view.noauth_extends',
                    $this->warder->get('frontend.view.extends', '_global.html')
                ),
                'langPrefix' => $this->warder->get('frontend.language.prefix', 'warder.'),
                'package' => WarderHelper::getPackage(),
            ]);

            // Paths
            $renderer->addPath(
                WARDER_SOURCE . '/Templates/' . $name . '/' . $app->get('language.locale'),
                PriorityQueue::LOW - 25
            );
            $renderer->addPath(
                WARDER_SOURCE . '/Templates/' . $name . '/' . $app->get('language.default'),
                PriorityQueue::LOW - 25
            );
            $renderer->addPath(WARDER_SOURCE . '/Templates/' . $name, PriorityQueue::LOW - 25);
        } elseif ($this->warder->isAdmin()) {
            // Extends
            $view['warder'] = new Data([
                'extends' => $this->warder->get('admin.view.extends', '_global.admin.admin'),
                'noauthExtends' => $this->warder->get(
                    'admin.view.noauth_extends',
                    $this->warder->get('admin.view.extends', '_global.admin.admin')
                ),
                'langPrefix' => $this->warder->get('admin.language.prefix', 'warder.'),
                'package' => WarderHelper::getPackage(),
            ]);

            // Paths
            $renderer->addPath(
                WARDER_SOURCE_ADMIN . '/Templates/' . $name . '/' . $app->get('language.locale'),
                PriorityQueue::LOW - 25
            );
            $renderer->addPath(
                WARDER_SOURCE_ADMIN . '/Templates/' . $name . '/' . $app->get('language.default'),
                PriorityQueue::LOW - 25
            );
            $renderer->addPath(WARDER_SOURCE_ADMIN . '/Templates/' . $name, PriorityQueue::LOW - 25);
        }
    }
}
