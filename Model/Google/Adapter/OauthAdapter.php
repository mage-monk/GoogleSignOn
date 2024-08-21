<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Google\Adapter;

use Google\Client;
use Google\Service\Oauth2;

class OauthAdapter
{
    /**
     * Create a new \Google\Service\Oauth2 instance
     *
     * @param Client $client
     * @return Oauth2
     */
    public function create(Client $client): Oauth2
    {
        return new Oauth2($client);
    }
}
