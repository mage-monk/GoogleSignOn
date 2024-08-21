<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Model;

use Magento\Framework\Encryption\EncryptorInterface;

/**
 * State Serializer
 */
class StateSerializer
{
    /**
     * Constant
     */
    private const DELIMITER = '_';

    /**
     * Initialization
     *
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        private readonly EncryptorInterface $encryptor
    ) {
    }

    /**
     * Encrypt the parameters
     *
     * @param ...$parts
     * @return string
     */
    public function serialize(...$parts): string
    {
        $state = implode(self::DELIMITER, $parts);

        return $this->encryptor->encrypt($state);
    }

    /**
     * Decrypt the string
     *
     * @param string $state
     * @return array
     */
    public function deserialize(string $state): array
    {
        return explode(self::DELIMITER, $this->encryptor->decrypt($state));
    }
}
