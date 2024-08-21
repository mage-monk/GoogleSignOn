/**
 * @api
 */
define([
    'jquery',
    'ko',
    'underscore',
    'mage/url'
], function ($, ko, _, urlBuilder) {
    'use strict';

    let isActive = false,
        googleUserData = {firstname: null, lastname: null, email: null, registration_source: 0},
        pathName = window.location.pathname;

    if (window.googleSignOn !== undefined) {
        isActive = window.googleSignOn.isActive;
        googleUserData = window.googleSignOn.googleUserData;
    }

    return {
        isActive: isActive,
        googleUserData: googleUserData,
        actionName: pathName.trim(),

        /**
         * @returns String
         */
        getActionName: function () {
            let actionName = pathName,
                checkoutRegExp = /checkout/,
                cartRegExp = /checkout\/cart/,
                createRegExp = /customer\/account\/create/;

            if (checkoutRegExp.test(pathName) && !cartRegExp.test(pathName)) {
                return 'checkout';
            }

            if (createRegExp.test(pathName)) {
                return 'create';
            }

            return actionName.trim();
        },

        /**
         * @returns String
         */
        getAfterAuthUrl: function () {
            return urlBuilder.build('checkout');
        }
    };
});
