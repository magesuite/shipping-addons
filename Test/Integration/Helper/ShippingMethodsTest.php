<?php

namespace MageSuite\ShippingAddons\Test\Integration\Helper;

class ShippingMethodsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\ShippingAddons\Helper\ShippingMethods
     */
    protected $shippingMethodsHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->shippingMethodsHelper = $this->objectManager->get(\MageSuite\ShippingAddons\Helper\ShippingMethods::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store carriers/freeshipping/active 1
     * @magentoConfigFixture current_store carriers/freeshipping/free_shipping_subtotal 40
     * @magentoConfigFixture current_store carriers/flatrate1/active 1
     * @magentoConfigFixture current_store carriers/flatrate1/minimum_subtotal_for_free_shipping 100
     */
    public function testItReturnFreeShippingMethod()
    {
        $freeShippingAmount = $this->shippingMethodsHelper->getMinimumFreeShippingAmount();

        $this->assertEquals(40, $freeShippingAmount);
    }

    /**
     * @magentoAppArea frontend
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoConfigFixture current_store carriers/freeshipping/active 0
     * @magentoConfigFixture current_store carriers/flatrate1/active 0
     */
    public function testItReturnNullWhenNoFreeShippingMethods()
    {
        $freeShippingAmount = $this->shippingMethodsHelper->getMinimumFreeShippingAmount();

        $this->assertNull($freeShippingAmount);
    }
}
