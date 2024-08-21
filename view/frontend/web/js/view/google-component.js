define([
    'jquery',
    'uiComponent',
    'ko',
    'Magento_Customer/js/model/customer',
    'MageMonk_GoogleSignOn/js/model/config-data',
    'Magento_Checkout/js/checkout-data',
    'Magento_Ui/js/model/messageList',
    'mage/translate'

    ], function ($, Component, ko, customer, googleConfig, checkoutData, messageList, $t) {
        'use strict';

        let googleUserData = googleConfig.googleUserData;

        window.addEventListener("message", function(event) {
            let wrongPasswordMessage = $.mage.__('The account sign-in was incorrect. Please check your details and try again or <a href="%1">click here</a> to create an account').replace('%1', '/customer/account/create');
            if (event.data.event_id === 'social_sso_error' && event.origin === location.origin) {
                if (event.data.type === 'wrongPasswordErrorMessage') {
                    messageList.addErrorMessage({ message: wrongPasswordMessage });
                } else {
                    messageList.addErrorMessage({ message: $.mage.__(event.data.message) });
                }
            }
        });

        return Component.extend({
            defaults: {
                template: 'MageMonk_GoogleSignOn/button'
            },

            initialize: function () {
                let checkoutRegExp = /checkout/,
                    cartRegExp = /checkout\/cart/;

                if (checkoutRegExp.test(googleConfig.getActionName())) {
                    this._validatedEmailValue();
                    this._shippingAddressFromData();
                }

                this._super();
            },

            /**
             * Flag
             * @var {Boolean}
             */
            isActive: googleConfig.isActive,

            /**
             * GoogleData
             * @var {$ObjMap}
             */
            googleUserData: googleConfig.googleUserData,

            /**
             * Check if customer is loggedIn Flag
             * @var {Boolean}
             */
            isCustomerLoggedIn: customer.isLoggedIn,

            /**
             * Check if customer is loggedIn Flag
             * @var {String}
             */
            actionName: googleConfig.getActionName(),

            /**
             * Get after auth url
             * @var {String}
             */
            afterAuthUrl: googleConfig.getAfterAuthUrl(),

            /**
             * Setting the email input field value pulled from persistence storage
             * @private
             */
            _validatedEmailValue: function () {
                if (googleUserData.email) {
                    checkoutData.setInputFieldEmailValue(googleUserData.email);
                    checkoutData.setValidatedEmailValue(googleUserData.email);
                    checkoutData.setCheckedEmailValue('');
                }
            },

            /**
             * Setting the shipping address pulled from persistence storage
             * @private
             */
            _shippingAddressFromData: function () {
                checkoutData.setShippingAddressFromData(null);
                if (googleUserData.firstname && googleUserData.lastname) {
                    checkoutData.setShippingAddressFromData({'firstname':googleUserData.firstname,'lastname':googleUserData.lastname});
                }
            }
        });
    }
);
