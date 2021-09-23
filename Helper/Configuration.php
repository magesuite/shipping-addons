<?php

namespace MageSuite\ShippingAddons\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CARRIERS_CONFIGURATION_PATH = 'carriers/%s/%s';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfig;
    }

    public function isFreeShippingEnabled(string $code, string $activeField)
    {
        $configPath = sprintf(self::CARRIERS_CONFIGURATION_PATH, $code, $activeField);

        return $this->scopeConfig->getValue($configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getFreeShippingSubtotal(string $code, string $subtotalField)
    {
        $configPath = sprintf(self::CARRIERS_CONFIGURATION_PATH, $code, $subtotalField);

        return $this->scopeConfig->getValue($configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getFreeShippingTitle(string $code)
    {
        $configPath = sprintf(self::CARRIERS_CONFIGURATION_PATH, $code, 'title');

        return $this->scopeConfig->getValue($configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
