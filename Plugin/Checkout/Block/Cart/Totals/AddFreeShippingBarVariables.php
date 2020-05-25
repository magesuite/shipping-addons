<?php

namespace MageSuite\ShippingAddons\Plugin\Checkout\Block\Cart\Totals;

class AddFreeShippingBarVariables
{
    const FREE_SHIPPING_BAR_CONFIG_PATH = 'components/block-totals/children/free_shipping_bar/config';
    /**
     * @var \Magento\Framework\Stdlib\ArrayManager
     */
    protected $arrayManager;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $taxHelper;

    /**
     * @var \MageSuite\ShippingAddons\Helper\ShippingMethods
     */
    protected $shippingMethodsHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\Stdlib\ArrayManager $arrayManager,
        \Magento\Tax\Helper\Data $taxHelper,
        \MageSuite\ShippingAddons\Helper\ShippingMethods $shippingMethodsHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->arrayManager = $arrayManager;
        $this->taxHelper = $taxHelper;
        $this->shippingMethodsHelper = $shippingMethodsHelper;
        $this->storeManager = $storeManager;
    }

    public function afterGetJsLayout(\Magento\Checkout\Block\Cart\Totals $subject, $jsLayout)
    {
        $jsLayout = json_decode($jsLayout, true);

        $freeShippingBarConfig = $this->arrayManager->get(self::FREE_SHIPPING_BAR_CONFIG_PATH, $jsLayout);

        if (!empty($freeShippingBarConfig)) {
            $freeShippingBarConfig['freeShippingFrom'] = $this->shippingMethodsHelper->getMinimumFreeShippingAmount();
            $freeShippingBarConfig['priceFormat'] = $this->taxHelper->getPriceFormat($this->storeManager->getStore()->getId());

            $jsLayout = $this->arrayManager->set(self::FREE_SHIPPING_BAR_CONFIG_PATH, $jsLayout, $freeShippingBarConfig);
        }

        return json_encode($jsLayout);
    }
}
