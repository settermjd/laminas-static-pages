<?php

declare(strict_types=1);

namespace StaticPages\Test;

use PHPUnit\Framework\TestCase;
use StaticPages\ConfigProvider;
use StaticPages\Handler\StaticPagesHandler;
use StaticPages\Handler\StaticPagesHandlerFactory;

/**
 * Class ConfigProviderTest
 * @package StaticPages\Test
 * @covers \StaticPages\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    public function testCanGetDependencies()
    {
        $provider = new ConfigProvider();
        $this->assertEquals(
            $provider->getDependencies(),
            [
                'factories' => [
                    StaticPagesHandler::class => StaticPagesHandlerFactory::class,
                ]
            ]
        );
    }

    public function testReturnsTheExpectedConfiguration()
    {
        $provider = new ConfigProvider();
        $configuration = $provider();

        $this->assertIsArray($configuration);
        $this->assertArrayHasKey('dependencies', $configuration);
    }
}
