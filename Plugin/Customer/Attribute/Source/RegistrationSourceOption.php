<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Plugin\Customer\Attribute\Source;

use MageMonk\SocialSignOn\Model\Customer\Attribute\Source\RegistrationSourceType;
use MageMonk\GoogleSignOn\Model\Customer\Attribute\Source\Option;

class RegistrationSourceOption
{
    /**
     * After get all option plugin
     *
     * @param RegistrationSourceType $subject
     * @param array $options
     * @return array
     */
    public function afterGetAllOptions(
        RegistrationSourceType $subject,
        array $options
    ): array {

        $options [] = ['value' => Option::GOOGLE, 'label' => __('Google')];

        return $options;
    }
}
