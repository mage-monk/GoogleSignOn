define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/translate',
    'jquery-ui-modules/widget'
], function ($, customerData, $t) {
    'use strict';

    window.addEventListener("message", function(event) {
        let wrongPasswordMessage = $.mage.__('The account sign-in was incorrect. Please check your details and try again or <a href="%1">click here</a> to create an account').replace('%1', '/customer/account/create');
        if (event.data.event_id === 'social_sso_error' && event.origin === location.origin) {
            if (event.data.type === 'wrongPasswordErrorMessage') {
                customerData.set('messages', {
                    messages: [{
                        text: wrongPasswordMessage,
                        type: 'error'
                    }]
                });
            } else {
                customerData.set('messages', {
                    messages: [{
                        text: $.mage.__(event.data.message),
                        type: 'error'
                    }]
                });
            }
        }
    });

    $.widget('google.authorization', {
        options: {
            centerBrowser: 0,
            centerScreen: 0,
            height: 500,
            left: 50,
            location: 0,
            menubar: 0,
            resizable: 1,
            scrollbars: 1,
            status: 0,
            width: 500,
            authName: '_googleAuth',
            authUrl: null,
            top: 50,
            toolbar: 0,
            action: null,
            referer: null
        },

        /**
         * @private
         */
        _create: function () {
            this.element.on('click', $.proxy(this._openPopup, this));
        },

        /**
         * @param {jQuery.Event} event
         * @private
         */
        _openPopup: function(event) {
            let form = $('<form />', {
                action: this.options.authUrl,
                target: this.options.authName,
                method: 'post'
            });

            $('<input />', {
                name: 'state',
                type: 'hidden',
                value: this.options.action
            }).appendTo(form);

            $('<input />', {
                name: 'referer',
                type: 'hidden',
                value: this.options.referer
            }).appendTo(form);

            event.preventDefault();
            window.open('', this.options.authName, this._getPopupOptions(this));
            form.appendTo('body').submit().remove();
        },

        /**
         * @param {jQuery.Event} event
         * @private
         */
        _getPopupOptions: function (event) {
            let element = $(event.target),
                popupFeatures =
                    'height=' + this.options.height +
                    ',width=' + this.options.width +
                    ',toolbar=' + this.options.toolbar +
                    ',scrollbars=' + this.options.scrollbars +
                    ',status=' + this.options.status +
                    ',resizable=' + this.options.resizable +
                    ',location=' + this.options.location +
                    ',menuBar=' + this.options.menubar,
                centeredX,
                centeredY;

            if (this.options.centerBrowser) {
                centeredY = window.screenY + (window.outerHeight / 2 - this.options.height / 2);
                centeredX = window.screenX + (window.outerWidth / 2 - this.options.width / 2);
                popupFeatures += ',left=' + centeredX + ',top=' + centeredY;
            } else if (this.options.centerScreen) {
                centeredY = (screen.height - this.options.height) / 2;
                centeredX = (screen.width - this.options.width) / 2;
                popupFeatures += ',left=' + centeredX + ',top=' + centeredY;
            } else {
                popupFeatures += ',left=' + this.options.left + ',top=' + this.options.top;
            }
            return popupFeatures;
        }
    });

    return $.google.authorization;
});
