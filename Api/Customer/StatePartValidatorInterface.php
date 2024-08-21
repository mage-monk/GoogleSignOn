<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Api\Customer;

/**
 * Interface to validate
 */
interface StatePartValidatorInterface
{
    /**
     * Validate
     *
     * @param string $value
     * @return bool
     */
    public function validate(string $value): bool;
}
