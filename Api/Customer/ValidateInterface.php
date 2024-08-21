<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Api\Customer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Interface to validate
 */
interface ValidateInterface
{
    /**
     * Validate
     *
     * @param RequestInterface $request
     * @return void
     * @throws LocalizedException
     */
    public function validate(RequestInterface $request): void;
}
