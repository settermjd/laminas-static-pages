<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Handler;

use Mezzio\Exception\MissingDependencyException;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StaticPagesHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $router   = $this->getRouter($container);
        $template = $this->getTemplateRenderer($container);

        return new StaticPagesHandler($router, $template);
    }

    /**
     * @throws MissingDependencyException
     */
    public function getRouter(ContainerInterface $container): RouterInterface
    {
        if ($container->has(RouterInterface::class)) {
            return $container->get(RouterInterface::class);
        }

        throw new MissingDependencyException("RouterInterface object not found in the container");
    }

    /**
     * @throws MissingDependencyException
     */
    public function getTemplateRenderer(ContainerInterface $container): TemplateRendererInterface
    {
        if ($container->has(TemplateRendererInterface::class)) {
            return $container->get(TemplateRendererInterface::class);
        }

        throw new MissingDependencyException("TemplateRendererInterface object not found in the container");
    }
}
