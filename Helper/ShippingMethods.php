<?php

namespace MageSuite\ShippingAddons\Helper;

class ShippingMethods extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \MageSuite\ShippingAddons\Service\FreeShippingMethodsProvider
     */
    protected $freeShippingMethodsProvider;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\ShippingAddons\Service\FreeShippingMethodsProvider $freeShippingMethodsProvider
    ) {
        parent::__construct($context);

        $this->freeShippingMethodsProvider = $freeShippingMethodsProvider;
    }

    public function getMinimumFreeShippingAmount()
    {
        $minimumAmount = null;

        foreach ($this->freeShippingMethodsProvider->getShippingMethodsWithFreeShipping() as $methodData) {
            if (!$minimumAmount || (int) $methodData['value'] < $minimumAmount) {
                $minimumAmount = (int) $methodData['value'];
            }
        }

        return $minimumAmount;
    }
}
