<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Customer\Validation;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use MageMonk\GoogleSignOn\Api\Customer\ValidateInterface;
use MageMonk\GoogleSignOn\Model\StateSerializer;

class StateValidator implements ValidateInterface
{
    /**
     * Constant
     */
    public const VAR_NAME = 'state';

    /**
     * Initialization
     *
     * @param StateSerializer $stateSerializer
     * @param array $statePartsValidator
     */
    public function __construct(
        private readonly StateSerializer $stateSerializer,
        private readonly array           $statePartsValidator
    ) {
    }

    /**
     * @inheritDoc
     */
    public function validate(RequestInterface $request): void
    {
        $state = $request->getParam(self::VAR_NAME, '');
        $e = new LocalizedException(__('Invalid request!'));
        if (!$state) {
            throw $e;
        }
        try {
            $parts = $this->stateSerializer->deserialize($state);
        } catch (\Exception $exception) {
            throw $e;
        }
        foreach ($parts as $key => $part) {
            $key = (string)$key;
            if (!isset($this->statePartsValidator[$key])) {
                continue;
            }
            $statePartValidator = $this->statePartsValidator[$key];
            if (!$statePartValidator->validate($part)) {
                throw $e;
            }
        }
    }
}
