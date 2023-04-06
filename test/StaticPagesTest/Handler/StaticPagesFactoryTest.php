<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Test\Handler;

use Mezzio\Exception\MissingDependencyException;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Settermjd\StaticPages\Handler\StaticPagesHandler;
use Settermjd\StaticPages\Handler\StaticPagesHandlerFactory;

class StaticPagesFactoryTest extends TestCase
{
    /** @var MockObject&ContainerInterface  */
    protected $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testFactoryWithRouteAndTemplate(): void
    {
        $router            = $this->createMock(RouterInterface::class);
        $templateInterface = $this->createMock(TemplateRendererInterface::class);

        $this->container
            ->method('has')
            ->willReturnOnConsecutiveCalls(true, true);
        $this->container
            ->method('get')
            ->willReturnOnConsecutiveCalls($router, $templateInterface);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container);

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }

    public function testFactoryWithoutRoute(): void
    {
        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage("RouterInterface object not found in the container");
        $this->container
            ->method('has')
            ->with(RouterInterface::class)
            ->willReturn(false);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container);

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }

    public function testFactoryWithRouteButWithoutTemplate(): void
    {
        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage("TemplateRendererInterface object not found in the container");

        $router = $this->createMock(RouterInterface::class);
        $this->container
            ->method('get')
            ->willReturn($router);
        $this->container
            ->method('has')
            ->willReturnOnConsecutiveCalls(true, false);

        $factory = new StaticPagesHandlerFactory();

        $page = $factory($this->container);

        $this->assertInstanceOf(StaticPagesHandler::class, $page);
    }
}
