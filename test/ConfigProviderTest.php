<?php

declare(strict_types=1);

namespace MezzioTest\StaticPages;

use Mezzio\StaticPages\ConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mezzio\StaticPages\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    public function testHasDependencies(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertArrayHasKey('dependencies', $configProvider());
    }
}
