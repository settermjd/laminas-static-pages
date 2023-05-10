<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Test\Handler;

use Mezzio\Exception\MissingDependencyException;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Settermjd\StaticPages\Handler\StaticPagesHandlerFactory;
use Settermjd\StaticPages\Handler\StaticPagesHandler;

class StaticPagesFactoryTest extends TestCase
{
    use ProphecyTrait;

    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactoryWithRouteAndTemplate()
    {
        $router = $this->prophesize(RouterInterface::class);
        $this->container->has(RouterInterface::class)->willReturn(true);
        $this->container->get(RouterInterface::class)->willReturn($router);

        $this->container->has(TemplateRendererInterface::class)->willReturn(true);
        $templateInterface = $this->prophesize(TemplateRendererInterface::class);
        $this->container
            ->get(TemplateRendererInterface::class)
            ->willReturn($templateInterface);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container->reveal());

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }

    public function testFactoryWithoutRoute()
    {
        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage("RouterInterface object not found in the container");
        $this->container->has(RouterInterface::class)->willReturn(false);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container->reveal());

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }

    public function testFactoryWithRouteButWithoutTemplate()
    {
        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage("TemplateRendererInterface object not found in the container");

        $router = $this->prophesize(RouterInterface::class);
        $this->container->has(RouterInterface::class)->willReturn(true);
        $this->container->get(RouterInterface::class)->willReturn($router);
        $this->container->has(TemplateRendererInterface::class)->willReturn(false);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container->reveal());

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }
}
