<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model;

use MageMonk\GoogleSignOn\Api\ConfigProviderInterface;
use MageMonk\GoogleSignOn\Api\ConfigInterface;
use Magento\Customer\Model\Session as CustomerSession;
use MageMonk\SocialSignOn\Model\Customer\SessionSourceKey;

class DefaultConfigProvider implements ConfigProviderInterface
{

    /**
     * Initialization
     *
     * @param ConfigInterface $storeConfig
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private readonly ConfigInterface $storeConfig,
        private readonly CustomerSession $customerSession
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return  [
            'isActive' => $this->storeConfig->isEnable(),
            'googleUserData' => $this->setGoogleData()
        ];
    }

    /**
     * Set google user information
     *
     * @return array
     */
    private function setGoogleData(): array
    {
        return [
            'firstname' => $this->customerSession->getFirstName(),
            'lastname' => $this->customerSession->getLastName(),
            'email' => $this->customerSession->getEmail(),
            SessionSourceKey::REGISTRATION_SOURCE_KEY => $this->customerSession->getRegistrationSource()
        ];
    }
}
