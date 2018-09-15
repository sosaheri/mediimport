<?php
if( ! function_exists( 'yith_wocc_get_custom_style' ) ){
	/**
	 * Get plugin custom style
	 *
	 * @since 1.0.5
	 * @author Francesco Licandro
	 * @return string
	 */
	function yith_wocc_get_custom_style(){

		$bkg        = get_option( 'yith-wocc-button-background' );
		$color      = get_option( 'yith-wocc-button-text' );
		$bkg_h      = get_option( 'yith-wocc-button-background-hover' );
		$color_h    = get_option( 'yith-wocc-button-text-hover' );
		
		$custom = ".yith-wocc-button{background-color:{$bkg} !important;color:{$color} !important;}
                .yith-wocc-button:hover{background-color:{$bkg_h} !important;color:{$color_h} !important;}";

		return apply_filters( 'yith_wocc_get_custom_style', $custom );
	}
}