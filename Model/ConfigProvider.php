<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model;

use MageMonk\GoogleSignOn\Api\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ConfigProviderInterface[]
     */
    private array $configProviders;

    /**
     * Initialization
     *
     * @param ConfigProviderInterface[] $configProviders
     */
    public function __construct(
        array $configProviders
    ) {
        $this->configProviders = $configProviders;
    }

    /**
     * Retrieve assoc array of configuration
     *
     * @inheirtdoc
     */
    public function getConfig(): array
    {
        $config = [];
        foreach ($this->configProviders as $configProvider) {
            $config = array_merge_recursive($config, $configProvider->getConfig());
        }
        return $config;
    }
}
