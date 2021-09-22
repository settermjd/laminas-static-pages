<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Test\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Exception\InvalidArgumentException;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Settermjd\StaticPages\Handler\StaticPagesHandler;

final class StaticPagesHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    /** @var RouterInterface|ObjectProphecy */
    protected $router;

    /** @var TemplateRendererInterface|ObjectProphecy $renderer */
    private $renderer;

    /** @var ServerRequestInterface|ObjectProphecy $request */
    private $request;

    /** @var RouteResult|ObjectProphecy $routerResult */
    private $routeResult;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->router = $this->prophesize(RouterInterface::class);
        $this->renderer = $this->prophesize(TemplateRendererInterface::class);
        $this->request = $this->prophesize(ServerRequestInterface::class);
        $this->routeResult = $this->prophesize(RouteResult::class);
    }

    public function testReturnsHtmlResponseWhenRequestedRouteIsNamedCorrectly()
    {
        $this->renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $this->renderer->reveal()
        );

        $this->routeResult->getMatchedRouteName()
            ->willReturn('static.terms');

        $this->request
            ->getAttribute(RouteResult::class)
            ->willReturn($this->routeResult->reveal());

        $response = $page->handle($this->request->reveal());

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function testThrowsExceptionWhenRequestedRouteHasNoName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Route has no name set");

        $this->renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $this->renderer->reveal()
        );

        $this->routeResult->getMatchedRouteName()
            ->willReturn(false);

        $this->request
            ->getAttribute(RouteResult::class)
            ->willReturn($this->routeResult->reveal());

        $page->handle($this->request->reveal());
    }

    /**
     * @dataProvider invalidRouteNameDataProvider
     */
    public function testThrowsExceptionWhenTheNameOfTheRequestedRouteDoesNotMatchTheExpectedFormat(string $routeName)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Route's name does not match the expected format.");

        $this->renderer
            ->render('static-pages::terms')
            ->willReturn('');

        $page = new StaticPagesHandler(
            $this->router->reveal(),
            $this->renderer->reveal()
        );

        $this->routeResult
            ->getMatchedRouteName()
            ->willReturn($routeName);

        $this->request
            ->getAttribute(RouteResult::class)
            ->willReturn($this->routeResult->reveal());

        $page->handle($this->request->reveal());
    }

    public function invalidRouteNameDataProvider(): array
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
