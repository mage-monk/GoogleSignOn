<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Plugin\Checkout;

use MageMonk\GoogleSignOn\Api\ConfigInterface;

/**
 * Class to load the google-component
 */
class LayoutProcessor
{
    /**
     * Initialization
     *
     * @param ConfigInterface $config
     */
    public function __construct(
        private readonly ConfigInterface $config
    ) {
    }

    /**
     * Render google component if enable
     *
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ): array {
        if ($this->config->isEnable()) {
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['customer-email']['children']['before-login-form']['children']
            ['google-signon'] = ['component' => 'MageMonk_GoogleSignOn/js/view/google-component'];

            // DGV checkout
            $jsLayout['components']['checkout']['children']['steps']['children']['buy-now-step']['children']
            ['customer-auth']['children']['before-account-menu']['children']
            ['google-signon'] = ['component' => 'MageMonk_GoogleSignOn/js/view/google-component'];
        }

        return $jsLayout;
    }
}
