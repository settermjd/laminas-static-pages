<?php

declare(strict_types=1);

namespace Settermjd\StaticPages\Test;

use PHPUnit\Framework\TestCase;
use Settermjd\StaticPages\ConfigProvider;
use Settermjd\StaticPages\Handler\StaticPagesHandler;
use Settermjd\StaticPages\Handler\StaticPagesHandlerFactory;

/**
 * @covers \Settermjd\StaticPages\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    public function testCanGetDependencies(): void
    {
        $provider = new ConfigProvider();
        $this->assertEquals(
            [
                'factories' => [
                    StaticPagesHandler::class => StaticPagesHandlerFactory::class,
                ],
            ],
            $provider->getDependencies()
        );
    }

    public function testReturnsTheExpectedConfiguration(): void
    {
        $provider      = new ConfigProvider();
        $configuration = $provider();

        $this->assertArrayHasKey('dependencies', $configuration);
    }
}
