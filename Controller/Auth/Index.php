<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Controller\Auth;

use Magento\Framework\Oauth\Helper\Oauth;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session as CustomerSession;
use MageMonk\GoogleSignOn\Model\Google\ClientFactory;
use MageMonk\GoogleSignOn\Model\StateSerializer;

/**
 * Perform google auth and set customer session
 */
class Index implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * Auth constructors
     *
     * @param ClientFactory $clientFactory
     * @param HttpRequest $httpRequest
     * @param RedirectFactory $resultRedirectFactory
     * @param RequestInterface $request
     * @param CustomerSession $customerSession
     * @param StateSerializer $stateSerializer
     * @param Oauth $oauth
     */
    public function __construct(
        private readonly ClientFactory           $clientFactory,
        private readonly HttpRequest             $httpRequest,
        private readonly RedirectFactory         $resultRedirectFactory,
        private readonly RequestInterface        $request,
        private readonly CustomerSession         $customerSession,
        private readonly StateSerializer         $stateSerializer,
        private readonly Oauth                   $oauth
    ) {
    }

    /**
     * Execute
     *
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $nonce = $this->createNonce();

        try {
            $sessionId = $this->customerSession->getSessionId();
            $state = $this->request->getPost('state');
            $referer = $this->request->getPost('referer');
            $state = $this->stateSerializer->serialize($state, $sessionId, $nonce);
            $authUrl = $this->getClientAuthUrl($state);
            $resultRedirect = $this->resultRedirectFactory->create();
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(__($e->getMessage()));
        }

        if (!empty($referer)) {
            $this->customerSession->setAfterAuthUrl($referer);
        }


        return $resultRedirect->setUrl($authUrl);
    }

    /**
     * Get auth url
     *
     * @param string $state
     * @return string
     * @throws NoSuchEntityException
     */
    private function getClientAuthUrl(string $state): string
    {
        $client = $this->clientFactory->create($state);
        return $client->createAuthUrl();
    }

    /**
     * @inheritdoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * Create Nonce in customer session
     *
     * @return string
     */
    private function createNonce(): string
    {
        $nonce = $this->oauth->generateRandomString(Oauth::LENGTH_NONCE);
        $this->customerSession->setSsoNonce($nonce);
        return $nonce;
    }
}
