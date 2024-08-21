<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Plugin\Account;

use MageMonk\GoogleSignOn\Api\ConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class for authentication popup
 */
class AuthenticationPopup
{
    /**
     * Initialization
     *
     * @param ConfigInterface $config
     * @param Json $serializer
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly Json $serializer
    ) {
    }

    /**
     * Render google sign on if enable
     *
     * @param \Magento\Customer\Block\Account\AuthenticationPopup $subject
     * @param string $result
     * @return bool|string
     */
    public function afterGetJsLayout(
        \Magento\Customer\Block\Account\AuthenticationPopup $subject,
        string $result
    ): bool|string {
        $layout = $this->serializer->unserialize($result);
        if ($this->config->isEnable()) {
            $layout['components']['authenticationPopup']['children']['google-sign-on'] = [
                'component' => 'MageMonk_GoogleSignOn/js/view/google-component',
                'displayArea' => 'before'
            ];
        }
        return $this->serializer->serialize($layout);
    }
}
