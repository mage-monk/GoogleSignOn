<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Api;

/**
 * Interface ConfigProviderInterface
 * @api
 */
interface ConfigProviderInterface
{
    /**
     * Retrieve assoc array of configuration
     *
     * @return array
     */
    public function getConfig(): array;
}
