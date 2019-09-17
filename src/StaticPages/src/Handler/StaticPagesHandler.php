<?php

declare(strict_types=1);

namespace StaticPages\Handler;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Exception\InvalidArgumentException;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class StaticPagesHandler implements RequestHandlerInterface
{
    const TEMPLATE_NS = 'static-pages';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * StaticPagesHandler constructor.
     * @param RouterInterface $router
     * @param TemplateRendererInterface|null $template
     */
    public function __construct(RouterInterface $router, TemplateRendererInterface $template)
    {
        $this->router   = $router;
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return HtmlResponse
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var RouteResult $routeName */
        $routeName = ($request->getAttribute(RouteResult::class))->getMatchedRouteName();

        if ($routeName === false) {
            throw new InvalidArgumentException('Route has no name set');
        }

        $templateName = sprintf('%s::%s', self::TEMPLATE_NS, substr($routeName, strrpos($routeName, '.') + 1));

        return new HtmlResponse($this->template->render($templateName));
    }
}
