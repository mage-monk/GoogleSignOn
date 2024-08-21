<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Google;

use Google\Client;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageMonk\GoogleSignOn\Api\ConfigInterface;
use MageMonk\GoogleSignOn\Model\Google\Adapter\ClientAdapter;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ClientFactory
 */
class ClientFactory
{
    /**
     * Constants
     */
    private const CALLBACK_URI = 'googlesignon/callback';

    /**
     * GetGoogleClientFactory constructor
     *
     * @param ClientAdapter $googleClientAdapter
     * @param ConfigInterface $configInterface
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly ClientAdapter         $googleClientAdapter,
        private readonly ConfigInterface       $configInterface,
        private readonly StoreManagerInterface $storeManager,
        private readonly UrlInterface $urlBuilder
    ) {
    }

    /**
     * Get configured Google client
     *
     * @param string $state
     * @return Client
     * @throws NoSuchEntityException
     */
    public function create(string $state): Client
    {
        try {
            $googleClient = $this->googleClientAdapter->create();

            $clientId = $this->configInterface->getClientId() ?: '';

            $clientSecret = $this->configInterface->getClientSecret() ?: '';

            $storeName = $this->storeManager->getStore()->getName();

            $callbackUri = $this->urlBuilder->getUrl(self::CALLBACK_URI);
            $googleClient->setApplicationName($storeName);
            $googleClient->setClientId($clientId);
            $googleClient->setClientSecret($clientSecret);
            $googleClient->setRedirectUri($callbackUri);
            $googleClient->setState($state);
            $googleClient->setPrompt('consent');
            $googleClient->addScope('email');
            $googleClient->addScope('profile');
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(__($e->getMessage()));
        }

        return $googleClient;
    }
}
