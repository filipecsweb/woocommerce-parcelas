jQuery(window).load(function () {
    (function () {
        $ = jQuery.noConflict();

        function fswp_variable_in_cash_calculation() {
            var final_price = $('.single_variation_wrap .price .amount:last').text();
            final_price = final_price.match(/\d+/g);
            var final_price_length = Number(final_price.length);
            var crude_price = '';
            var in_cash_html;

            var i;
            for (i = 1; i < final_price_length; i++) {
                crude_price += final_price[i - 1];
            }

            crude_price += '.' + final_price[final_price_length - 1];

            crude_price = Number(crude_price);

            var factor = Number(in_cash_discount.replace(',', '.'));

            if (in_cash_discount_type == 0) { // %     
                factor = 1 - (factor / 100);

                var discount_price = crude_price * factor;
            }
            else if (in_cash_discount_type == 1) { // Fixed
                var discount_price = crude_price - in_cash_discount;
            }

            in_cash_html = in_cash_prefix + ' <span class="amount">' + formatMoney(cur_symbol, discount_price, 2, dec_sep, th_sep, cur_pos) + '</span> ' + in_cash_suffix;

            $('.fswp_variable_in_cash_calculation').html(in_cash_html);
        }

        var default_variation = Number($('.single_variation .price').length);

        if (default_variation) {
            fswp_variable_in_cash_calculation();
        }
        $('.variations select').bind('change', function () {
            setTimeout(function () {
                fswp_variable_in_cash_calculation();
            }, 100);
        });
    })();
});