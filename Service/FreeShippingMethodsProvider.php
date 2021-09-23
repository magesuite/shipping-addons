<?php

namespace MageSuite\ShippingAddons\Service;

class FreeShippingMethodsProvider
{
    const DEFAULT_FREE_SHIPPING_METHOD_NAME = 'freeshipping';
    const DEFAULT_FREE_SHIPPING_ACTIVE_FIELD = 'active';
    const DEFAULT_FREE_SHIPPING_SUBTOTAL_FIELD = 'free_shipping_subtotal';
    const EXTERNAL_METHODS_FREE_SHIPPING_ACTIVE_FIELD = 'free_shipping_enable';

    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $shippingConfig;

    /**
     * @var \MageSuite\ShippingAddons\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $customFreeShippingFieldsMap = [];

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        \MageSuite\ShippingAddons\Helper\Configuration $configuration,
        array $customFreeShippingFieldsMap = []
    ) {
        $this->shippingConfig = $shippingConfig;
        $this->configuration = $configuration;
        $this->customFreeShippingFieldsMap = $customFreeShippingFieldsMap;
    }

    public function getShippingMethodsWithFreeShipping()
    {
        $activeCarriers = $this->shippingConfig->getActiveCarriers();
        $methods = [];

        foreach ($activeCarriers as $code => $model) {
            $activeField = $this->getActiveField($code);
            $subtotalField = $this->getSubtotalField($code);

            $isFreeShippingEnabled = $this->configuration->isFreeShippingEnabled($code, $activeField);

            if (!$isFreeShippingEnabled) {
                continue;
            }

            $freeShippingSubtotal = $this->configuration->getFreeShippingSubtotal($code, $subtotalField);

            $freeShippingTitle = $this->configuration->getFreeShippingTitle($code);

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
