define([
    'ko',
    'underscore',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Catalog/js/price-utils',
    'mage/translate',
], function(ko, _, Component, customerData, priceUtils, $t) {
    'use strict';

    var percentage;
    var subtotalAmount;
    var amountLeft;
    var amountLeftFormatted;

    return Component.extend({
        displaySubtotal: ko.observable(true),

        /**
         * @override
         */
        initialize: function(config) {
            this._super();
            this.cart = customerData.get('cart');
            this.freeShippingPrice = config.freeShippingFrom;
            this.localePriceFormat = config.priceFormat;
        },

        getTotalCartItems: function() {
            return this.cart().summary_count;
        },

        isDisplayed: function() {
            return this.getTotalCartItems() > 0;
        },

        getPercentage: function() {
            subtotalAmount = this.cart().subtotalAmount;

            if (subtotalAmount > this.freeShippingPrice) {
                subtotalAmount = this.freeShippingPrice;
            }

            percentage = (subtotalAmount * 100) / this.freeShippingPrice;
            return percentage;
        },

        isDisplayAmountLeft: function() {
            return this.freeShippingPrice - this.cart().subtotalAmount > 0;
        },

        getFormattedPrice: function(price, round) {
            var rounded = {
                precision: 0,
                requiredPrecision: 0,
            };
            var priceFormat =
                round || (Number(price) === price && price % 1 === 0)
                    ? _.extend({}, this.localePriceFormat, rounded)
                    : this.localePriceFormat;

            return priceUtils.formatPrice(price, priceFormat);
        },

        getAmountLeft: function() {
            amountLeft = this.freeShippingPrice - this.cart().subtotalAmount;
            amountLeftFormatted = this.getFormattedPrice(amountLeft, false);

            return amountLeftFormatted;
        },

        getAmountLeftText: function() {
            return $t('<span>%1</span> more for free shipping').replace(
                '%1',
                this.getAmountLeft()
            );
        },
    });
});
