<?php
/**
 * Copyright © Blackhawk Network. All rights reserved.
 * See LICENSE_BHN.txt for license details.
 */
declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Controller\Callback;

use Google\Service\Oauth2\Userinfo;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use MageMonk\GoogleSignOn\Api\Customer\ValidateInterface;
use MageMonk\GoogleSignOn\Model\Customer\Attribute\Source\Option;
use MageMonk\GoogleSignOn\Model\Google\UserInfoFactory;
use MageMonk\GoogleSignOn\Model\StateSerializer;
use MageMonk\SocialSignOn\Api\Customer\SsoLoginManagementInterface;
use Psr\Log\LoggerInterface;

/**
 * Perform google auth and set customer session
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Index implements HttpGetActionInterface
{
    private const CUSTOMER_ACCOUNT_CREATE = 'create';

    private const CHECKOUT_ROUTES = 'checkout';

    private const MATCH_ROUTES = [
        self::CUSTOMER_ACCOUNT_CREATE,
        self::CHECKOUT_ROUTES
    ];

    /**
     * Index constructor
     *
     * @param CustomerSession $customerSession
     * @param UserInfoFactory $userInfoFactory
     * @param RequestInterface $request
     * @param ValidateInterface $validate
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $logger
     * @param StateSerializer $stateSerializer
     * @param SsoLoginManagementInterface $ssoLoginManagement
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly CustomerSession             $customerSession,
        private readonly UserInfoFactory             $userInfoFactory,
        private readonly RequestInterface            $request,
        private readonly ValidateInterface           $validate,
        private readonly PageFactory                 $resultPageFactory,
        private readonly LoggerInterface             $logger,
        private readonly StateSerializer             $stateSerializer,
        private readonly SsoLoginManagementInterface $ssoLoginManagement
    ) {
    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $authCode = $this->request->getParam('code', '');
        $state = $this->request->getParam('state', '');
        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()->getBlock('google.callback');

        try {
            if ($this->customerSession->isLoggedIn()) {
                return $resultPage;
            }

            $this->validate->validate($this->request);
            list($state) = $this->stateSerializer->deserialize($state);
            $googleUserInfo = $this->userInfoFactory->create($authCode, $state);
            $loginStatus = $this->setCustomer($googleUserInfo, $state);
        } catch (NoSuchEntityException $e) {
            $block->setData('status', false);
            $block->setData('message', __('Not found'));
            $block->setData('type', 'wrongPasswordErrorMessage');
            return $resultPage;
        } catch (UserLockedException $e) {
            $block->setData('status', false);
            $block->setData('message', __($e->getMessage()));
            $block->setData('type', 'Exception');
            return $resultPage;
        } catch (\Exception $e) {
            $this->logger->debug("Callback index : ".$e->getMessage());
            $block->setData('status', false);
            $block->setData('message', __("We couldn't connect your social account."));
            $block->setData('type', 'Exception');
            return $resultPage;
        }

        if (!$loginStatus) {
            $block->setData('status', false);
            $block->setData('message', __('Something went wrong, please try again after sometime.'));
            $block->setData('type', 'login_status');
            return $resultPage;
        }

        $refererUrl = $this->customerSession->getAfterAuthUrl();
        $block->setData('message', __('Success'));
        $block->setData('status', true);
        $block->setData('state', $state);
        $block->setData('refererUrl', $refererUrl);
        $block->setData('isCustomerLoggedIn', $this->customerSession->isLoggedIn());
        return $resultPage;
    }

    /**
     * Set customer as login or set form data
     *
     * @param Userinfo $googleUserInfo
     * @param string $state
     * @return bool
     * @throws NoSuchEntityException|LocalizedException|UserLockedException
     */
    private function setCustomer(Userinfo $googleUserInfo, string $state): bool
    {
        try {
            $loginStatus = $this->ssoLoginManagement->login($googleUserInfo->getEmail());
        } catch (NoSuchEntityException $e) {
            $this->setFormData($googleUserInfo);
            if (in_array(strtok($state, '_'), self::MATCH_ROUTES)) {
                return true;
            }
            throw $e;
        }  catch (UserLockedException $e) {
            throw new UserLockedException(__($e->getMessage()), $e);
        } catch (LocalizedException $e) {
            throw new LocalizedException($e);
        }

        return $loginStatus;
    }

    /**
     * Set form data
     *
     * @param Userinfo $googleUserInfo
     * @return void
     */
    private function setFormData(Userinfo $googleUserInfo): void
    {
        $this->customerSession->setCustomerFormData([
            'firstname' => $googleUserInfo->getGivenName(),
            'lastname' => $googleUserInfo->getFamilyName(),
            'email' => $googleUserInfo->getEmail()
        ]);

        $this->customerSession->setFirstName($googleUserInfo->getGivenName())
            ->setLastName($googleUserInfo->getFamilyName())
            ->setEmail($googleUserInfo->getEmail())
            ->setRegistrationSource(Option::GOOGLE);
    }
}
