<?php

declare(strict_types=1);

namespace MageMonk\GoogleSignOn\Block;

use Magento\Framework\View\Element\Template;
use MageMonk\GoogleSignOn\Model\ConfigProvider;
use Magento\Framework\Serialize\Serializer\Json;

class AuthenticationPopup extends Template
{
    /**
     * @var array
     */
    protected $jsLayout;

    /**
     * @var ConfigProvider
     */
    protected ConfigProvider $configProviders;

    /**
     * @var Json
     */
    private Json $serializer;

    /**
     * Initialization
     *
     * @param Template\Context $context
     * @param ConfigProvider $configProviders
     * @param array $data
     * @param Json|null $serializer
     */
    public function __construct(
        Template\Context $context,
        ConfigProvider $configProviders,
        array $data = [],
        Json $serializer = null,
    ) {
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->configProviders = $configProviders;
    }

    /**
     * Returns serialize jsLayout
     *
     * @return string
     */
    public function getJsLayout(): string
    {
        return $this->serializer->serialize($this->jsLayout);
    }

    /**
     * Returns google config
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->configProviders->getConfig();
    }

    /**
     * Return serialized config
     *
     * @return bool|string
     */
    public function getSerializedConfig(): bool|string
    {
        return $this->serializer->serialize($this->getConfig());
    }
}
