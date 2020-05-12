<?php

namespace MageSuite\ShippingAddons\Service;

class FreeShippingMethodsProvider
{
    const DEFAULT_FREE_SHIPPING_METHOD_NAME = 'freeshipping';
    const DEFAULT_FREE_SHIPPING_ACTIVE_FIELD = 'active';
    const DEFAULT_FREE_SHIPPING_SUBTOTAL_FIELD = 'free_shipping_subtotal';
    const EXTERNAL_METHODS_FREE_SHIPPING_ACTIVE_FIELD = 'free_shipping_enable';

    protected $customFreeShippingFieldsMap = [
        'flatrate1' => 'minimum_subtotal_for_free_shipping',
        'flatrate2' => 'minimum_subtotal_for_free_shipping'
    ];
    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $shippingConfig;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function getShippingMethodsWithFreeShipping()
    {
        $activeCarriers = $this->shippingConfig->getActiveCarriers();
        $methods = [];

        foreach ($activeCarriers as $code => $model) {
            $activeField = $this->getActiveField($code);
            $subtotalField = $this->getSubtotalField($code);

            $isFreeShippingEnabled = $this->scopeConfig->getValue(
                'carriers/' . $code . '/' . $activeField,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            if (!$isFreeShippingEnabled) {
                continue;
            }

            $freeShippingSubtotal = $this->scopeConfig->getValue(
                'carriers/' . $code . '/' . $subtotalField,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $freeShippingTitle = $this->scopeConfig->getValue(
                'carriers/' . $code . '/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $methods[$code] = [
                'title' => $freeShippingTitle,
                'value' => $freeShippingSubtotal
            ];
        }

        return $methods;
    }

    protected function getActiveField($code)
    {
        if ($code == self::DEFAULT_FREE_SHIPPING_METHOD_NAME) {
            return self::DEFAULT_FREE_SHIPPING_ACTIVE_FIELD;
        }

        if (isset($this->customFreeShippingFieldsMap[$code])) {
            return $this->customFreeShippingFieldsMap[$code];
        }

        return self::EXTERNAL_METHODS_FREE_SHIPPING_ACTIVE_FIELD;
    }

    protected function getSubtotalField($code)
    {
        if (isset($this->customFreeShippingFieldsMap[$code])) {
            return $this->customFreeShippingFieldsMap[$code];
        }

        return self::DEFAULT_FREE_SHIPPING_SUBTOTAL_FIELD;
    }
}
