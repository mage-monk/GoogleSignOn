define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'MageMonk_GoogleSignOn/js/model/config-data'
    ], function ($, ko, checkoutData, quote, googleConfig) {
        'use strict';

        let gEmail = window.checkoutConfig.validatedEmailValue ?? '',
            firstname = '',
            lastname = '',
            confirmEmail = false,
            registration_source = 0;

        if (googleConfig.isActive) {
            firstname = googleConfig.googleUserData.firstname;
            lastname = googleConfig.googleUserData.lastname;
            gEmail = googleConfig.googleUserData.email ?? gEmail;
            confirmEmail = true;
            registration_source = googleConfig.googleUserData.registration_source;
        }

        let mixin = {
                defaults: {
                    email: gEmail,
                    emailConfirmation: confirmEmail,
                    firstname: firstname,
                    lastname: lastname,
                    registration_source: registration_source
                }
            };

        return function (target) {
            return target.extend(mixin);
        };
    }
);
