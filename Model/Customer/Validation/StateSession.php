<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Customer\Validation;

use Magento\Customer\Model\Session as CustomerSession;
use MageMonk\GoogleSignOn\Api\Customer\StatePartValidatorInterface;

class StateSession implements StatePartValidatorInterface
{
    /**
     * Constant
     */
    public const VAR_NAME = 'state';

    /**
     * Initialization
     *
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private readonly CustomerSession $customerSession
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate(string $value): bool
    {
        return $this->customerSession->getSessionId() === $value;
    }
}
