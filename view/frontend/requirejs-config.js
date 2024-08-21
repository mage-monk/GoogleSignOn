/**
 * Copyright © Blackhawk Network. All rights reserved.
 * See LICENSE_BHN.txt for license details.
 */

var config = {
    paths: {
        googleAuthorization: 'MageMonk_GoogleSignOn/js/google-authorization'
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/form/element/email': {
                'MageMonk_GoogleSignOn/js/mixins/email-mixin': true
            }
        }
    }
};
