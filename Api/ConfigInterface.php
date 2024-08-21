<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Api;

/**
 * Interface to predefine the XMl path and get config values
 */
interface ConfigInterface
{
    /**
     * Is enabled
     *
     * @return bool
     */
    public function isEnable(): bool;

    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientId(): string;
}
