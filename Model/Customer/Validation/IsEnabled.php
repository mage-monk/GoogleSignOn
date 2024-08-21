<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Customer\Validation;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use MageMonk\GoogleSignOn\Api\ConfigInterface;
use MageMonk\GoogleSignOn\Api\Customer\ValidateInterface;

class IsEnabled implements ValidateInterface
{
    /**
     * Validate constructor
     *
     * @param ConfigInterface $configInterface
     */
    public function __construct(
        private readonly ConfigInterface $configInterface,
    ) {
    }

    /**
     * Validate
     *
     * @param RequestInterface $request
     * @return void
     * @throws LocalizedException
     */
    public function validate(RequestInterface $request): void
    {
        if (!$this->configInterface->isEnable()) {
            throw new LocalizedException(__('Invalid request!'));
        }
    }
}
