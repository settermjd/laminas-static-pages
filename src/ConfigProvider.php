<?php

declare(strict_types=1);

namespace Mezzio\StaticPages;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * @return array<mixed>
     */
    public function getDependencies(): array
    {
        return [];
    }
}
