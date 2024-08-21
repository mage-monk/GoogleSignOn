<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model\Customer;

use Magento\Framework\App\RequestInterface;
use MageMonk\GoogleSignOn\Api\Customer\ValidateInterface;

class ValidatorComposite implements ValidateInterface
{
    /**
     * @var ValidateInterface[]
     */
    private array $validators;

    /**
     * @param array $validators
     */
    public function __construct(array $validators)
    {
        foreach ($validators as $validator) {
            if (!$validator instanceof ValidateInterface) {
                throw new \InvalidArgumentException(
                    sprintf('Validator must implement: %s', ValidateInterface::class)
                );
            }
        }
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function validate(RequestInterface $request): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($request);
        }
    }
}
