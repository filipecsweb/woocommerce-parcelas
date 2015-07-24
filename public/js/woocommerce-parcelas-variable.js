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

        if(preco_cru <= valor_minimo){
            output = '';
        }
        else if(preco_cru > valor_minimo){
            var preco_parcelado = preco_cru / parcelas;
            preco_parcelado = preco_parcelado.toFixed(2);

            if(preco_parcelado < valor_minimo){
                var parcelas_menor = parcelas;
                while(parcelas_menor > 1 && preco_parcelado < valor_minimo){
                    parcelas_menor--;
                    preco_parcelado = preco_cru / parcelas_menor;
                    preco_parcelado = preco_parcelado.toFixed(2);
                }

                if(preco_parcelado > valor_minimo){
                    output = prefixo + ' ' + parcelas_menor + x_de + ' <span class="amount">' + cur_symbol + formatMoney(preco_parcelado, 2, sep_dec, sep_mil) + '</span> ' + sufixo;
                }
                else{
                    output = '';
                }
            }
            else{
                output = prefixo + ' ' + parcelas + x_de + ' <span class="amount">' + cur_symbol + formatMoney(preco_parcelado, 2, sep_dec, sep_mil) + '</span> ' + sufixo;
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