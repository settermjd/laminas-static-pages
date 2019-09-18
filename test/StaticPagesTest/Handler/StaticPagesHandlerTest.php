<?php

declare(strict_types=1);

namespace StaticPages\Test\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use StaticPages\Handler\StaticPagesHandler;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Exception\InvalidArgumentException;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class StaticPagesHandlerTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    /** @var RouterInterface|ObjectProphecy */
    protected $router;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->router    = $this->prophesize(RouterInterface::class);
    }

    public function testReturnsHtmlResponseWhenRequestedRouteIsNamedCorrectly()
    {
        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $renderer->reveal()
        );

        /** @var RouteResult|ObjectProphecy $routerResult */
        $routerResult = $this->prophesize(RouteResult::class);
        $routerResult->getMatchedRouteName()
            ->willReturn('static.terms');

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(RouteResult::class)
            ->willReturn($routerResult);
        $response = $page->handle($request->reveal());

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function testThrowsExceptionWhenRequestedRouteHasNoName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Route has no name set");

        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $renderer->reveal()
        );

        /** @var RouteResult|ObjectProphecy $routerResult */
        $routerResult = $this->prophesize(RouteResult::class);
        $routerResult->getMatchedRouteName()
            ->willReturn(false);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(RouteResult::class)
            ->willReturn($routerResult);
        $page->handle($request->reveal());
    }

    /**
     * @dataProvider invalidRouteNameDataProvider
     */
    public function testThrowsExceptionWhenTheNameOfTheRequestedRouteDoesNotMatchTheExpectedFormat(string $routeName)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Route's name does not match the expected format.");

        $renderer = $this->prophesize(TemplateRendererInterface::class);
        $renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $router = $this->prophesize(RouterInterface::class);

        $page = new StaticPagesHandler(
            $router->reveal(),
            $renderer->reveal()
        );

        /** @var RouteResult|ObjectProphecy $routerResult */
        $routerResult = $this->prophesize(RouteResult::class);
        $routerResult->getMatchedRouteName()
            ->willReturn($routeName);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(RouteResult::class)
            ->willReturn($routerResult);
        $page->handle($request->reveal());
    }

    public function invalidRouteNameDataProvider()
    {
        return [
            [
                'terms'
            ],
            [
                'shonky.terms'
            ],
            [
                'static.'
            ],
            [
                '.terms'
            ],
            [
                'static_terms'
            ],
        ];
    }
}
