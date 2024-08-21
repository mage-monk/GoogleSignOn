<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Google\Adapter;

use Google\Client;

/**
 * Class to create a new object with Google client
 */
class ClientAdapter
{
    /**
     * Converts Markdown text into another format
     *
     * @return Client
     */
    public function create(): Client
    {
        return new Client(
            ['approval_prompt' => 'force']
        );
    }
}
