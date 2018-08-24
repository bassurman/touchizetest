/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* eslint-disable no-undef */
// jscs:disable jsDoc

require([
    'jquery'
], function ($) {
    (function ( $ ) {
        $.fn.themeSwitcher = {
            init:function(){
                tsthis = this
                this.config = {
                    switchSelector: '#row_touchize_commmerce_config_touchize_commmerce_design_theme_type input[type="radio"]',
                };
                tsthis.defaultTheme();
                tsthis.initSwitcher();
                return this;
            },
            initSwitcher: function() {
                $(tsthis.config.switchSelector).change(function (){
                        var optionValue = $(this).val();
                    tsthis.switchType(optionValue);
                    }
                )
            },
            defaultTheme: function() {
                var checkedValue = $(tsthis.config.switchSelector + ':checked').val();
                tsthis.switchType(checkedValue);
            },
            switchType: function (optionValue) {
                if (optionValue) {
                    $('#theme-types').removeAttr('class');
                    $('#theme-types').addClass(optionValue);
                }
            }
        };
    }( jQuery ));

    $(document).ready(function() {
        $.fn.themeSwitcher.init();
    });

});
