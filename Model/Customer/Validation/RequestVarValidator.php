<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Customer\Validation;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use MageMonk\GoogleSignOn\Api\Customer\ValidateInterface;

/**
 * Request validator
 */
class RequestVarValidator implements ValidateInterface
{
    /**
     * Constructor
     *
     * @param string $requestVar
     */
    public function __construct(
        private readonly string $requestVar
    ) {
    }

    /**
     * @inheritdoc
     */
    public function validate(RequestInterface $request): void
    {
        if (!$request->getParam($this->requestVar, '')) {
            throw new LocalizedException(__('Invalid request!'));
        }
    }
}
