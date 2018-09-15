<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file. 
* Wordpress will use those functions instead of the original functions then.
*/

add_filter( 'woocommerce_currency_symbol', 'cambiar_simbolo_moneda', 10, 2 );
function cambiar_simbolo_moneda( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'USD':
            $currency_symbol = 'U$D ';
        break;
    }
    return $currency_symbol;
}