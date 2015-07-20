jQuery(window).load(function(){
    function fswp_calc(){
        var exp = /\d+/g;
        var preco = jQuery('.single_variation_wrap .price .amount:last').text();
        var preco = preco.match(exp);
        var preco_length = Number(preco.length);
        var preco_cru = '';
        var output;
                                         
        var i;
        for(i = 1; i < preco_length; i++){
            preco_cru += preco[i - 1];
        }

        preco_cru += '.' + preco[preco_length - 1];

        preco_cru = Number(preco_cru);

        if(preco_cru <= fswp_valor_minimo){
            output = '';
        }
        else if(preco_cru > fswp_valor_minimo){
            var preco_parcelado = preco_cru / fswp_parcelas;
            preco_parcelado = preco_parcelado.toFixed(2);

            if(preco_parcelado < fswp_valor_minimo){
                var fswp_parcelas_menor = fswp_parcelas;
                while(fswp_parcelas_menor > 1 && preco_parcelado < fswp_valor_minimo){
                    fswp_parcelas_menor--;
                    preco_parcelado = preco_cru / fswp_parcelas_menor;
                    preco_parcelado = preco_parcelado.toFixed(2);
                }

                if(preco_parcelado > fswp_valor_minimo){
                    output = fswp_prefixo + ' ' + fswp_parcelas_menor + fswp_x_de + ' <span class="amount">' + cur_symbol + formatMoney(preco_parcelado, 2, sep_dec, sep_mil) + '</span> ' + fswp_sufixo;
                }
                else{
                    output = '';
                }
            }
            else{
                output = fswp_prefixo + ' ' + fswp_parcelas + fswp_x_de + ' <span class="amount">' + cur_symbol + formatMoney(preco_parcelado, 2, sep_dec, sep_mil) + '</span> ' + fswp_sufixo;
            }
        }

        jQuery('.fswp_variable_installment').html(output);
    }

    var default_variation = Number(jQuery('.single_variation .price').length);

    if(default_variation){
        fswp_calc();
    }
    jQuery('.variations select').bind('change', function(){
        setTimeout(function(){
            fswp_calc();
        }, 100);       
    });    
});