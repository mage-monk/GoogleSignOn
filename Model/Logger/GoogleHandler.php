<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class GoogleHandler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * File path with name
     *
     * @var string
     */
    protected $fileName = '/var/log/magemonk/google-sign-on.log';
}
