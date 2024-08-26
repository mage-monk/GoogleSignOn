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
