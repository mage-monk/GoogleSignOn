<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MageMonk\GoogleSignOn\Api\ConfigInterface;

/**
 * To get the configuration values
 */
class Config implements ConfigInterface
{
    /**#@+
     * Constants for Google is enabled
     */
    private const XML_PATH_GOOGLE_IS_ENABLE = 'social_sign_on/google/is_enabled';

    /**#@+
     * Constants to get client id
     */
    private const XML_PATH_GOOGLE_CLIENT_ID = 'social_sign_on/google/client_id';

    /**#@+
     * Constants to get client secret
     */
    private const XML_PATH_GOOGLE_CLIENT_SECRET = 'social_sign_on/google/client_secret';
    /**#@-*/

    /**
     * Config constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @inheritdoc
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_GOOGLE_IS_ENABLE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @inheridoc
     */
    public function getClientSecret(): string
    {
        return $this->getConfigValue(self::XML_PATH_GOOGLE_CLIENT_SECRET);
    }

    /**
     * @inheirtdoc
     */
    public function getClientId(): string
    {
        return $this->getConfigValue(self::XML_PATH_GOOGLE_CLIENT_ID);
    }

    /**
     * Get config value
     *
     * @param string $xmlPath
     * @return bool|string
     */
    private function getConfigValue(string $xmlPath): bool|string
    {
        return $this->scopeConfig->getValue($xmlPath, ScopeInterface::SCOPE_WEBSITE);
    }
}
