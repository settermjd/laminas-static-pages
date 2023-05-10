<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Exception\InvalidArgumentException;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class StaticPagesHandler implements RequestHandlerInterface
{
    const ROUTE_NAME_PREFIX = 'static.';
    const TEMPLATE_NS = 'static-pages';

    private RouterInterface $router;
    private TemplateRendererInterface $template;

    public function __construct(RouterInterface $router, TemplateRendererInterface $template)
    {
        $this->router   = $router;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var string|false $routeName */
        $routeName = ($request->getAttribute(RouteResult::class))->getMatchedRouteName();

        if ($routeName === false) {
            throw new InvalidArgumentException('Route has no name set');
        }

        $templateName = sprintf('%s::%s', self::TEMPLATE_NS, $this->getRouteName($routeName));

        return new HtmlResponse($this->template->render($templateName));
    }

    public function getRouteName(string $routeName) : string
    {
        if (substr($routeName, 0, 7) !== self::ROUTE_NAME_PREFIX) {
            throw new InvalidArgumentException("Route's name does not match the expected format.");
        }

        if ((substr($routeName, 7)) === '') {
            throw new InvalidArgumentException("Route's name does not match the expected format.");
        }

        return substr($routeName, 7);
    }
}
