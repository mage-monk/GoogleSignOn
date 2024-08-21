<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Google;

use Google\Service\Oauth2\Userinfo;
use Magento\Framework\Exception\LocalizedException;
use MageMonk\GoogleSignOn\Model\Google\Adapter\OauthAdapter;

/**
 * Verify Google authentication code and return user information
 */
class UserInfoFactory
{
    /**
     * GoogleUserInfo constructors
     *
     * @param ClientFactory $clientFactory
     * @param OauthAdapter $oauthAdapter
     */
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly OauthAdapter $oauthAdapter
    ) {
    }

    /**
     * Authenticate through the Google Auth code response
     *
     * @param string $authCode
     * @param string $state
     * @return Userinfo
     * @throws LocalizedException
     */
    public function create(string $authCode, string $state): Userinfo
    {
        $googleClient = $this->clientFactory->create($state);

        $token = $googleClient->fetchAccessTokenWithAuthCode($authCode);
        if (isset($token['error']) || !empty($token['error_description'])) {
            throw new LocalizedException(__($token['error_description']));
        }

        if (empty($token['access_token'])) {
            throw new LocalizedException(__('Empty token response'));
        }

        $oauthResponse = $this->oauthAdapter->create($googleClient);
        return $oauthResponse->userinfo->get();
    }
}
