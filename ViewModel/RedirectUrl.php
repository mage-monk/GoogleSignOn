<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * This class to check if Google Sso is enabled
 * and is mobile view.
 */
class RedirectUrl implements ArgumentInterface
{
    /**
     * Initialization
     *
     * @param RedirectInterface $redirect
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private readonly RedirectInterface $redirect,
        private readonly CustomerSession $customerSession,
    ) {
    }

    /**
     * Get redirect url
     *
     * @return string
     */
    public function getRedirectUrlAfterLogin() : string
    {
        $afterAuthUrl = $this->customerSession->getAfterAuthUrl();
        $beforeAuthUrl = $this->customerSession->getBeforeAuthUrl();
        $refererUrl = $this->redirect->getRefererUrl();

        return ($afterAuthUrl ?: $beforeAuthUrl ?: $refererUrl);
    }
}
