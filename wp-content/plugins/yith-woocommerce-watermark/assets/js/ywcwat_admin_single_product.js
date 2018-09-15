jQuery(document).ready(function($){

    $(document).on( 'click', '#ywcwat_apply_product',function(e){
       e.preventDefault();
        
        var t = $(this),
            product_id = $(this).data('product_id'),
            data = {
                product_id:product_id,
                action : ywcwat_product_param.actions.save_watermark_on_single_product
            };

        $.ajax({
            type: 'POST',
            url: ywcwat_product_param.ajax_url,
            data: data,
            dataType: 'json',
            beforeSend: function(){
                t.siblings( '.ajax-loading' ).css( 'visibility', 'visible' );
            },
            complete: function(){
                t.siblings( '.ajax-loading' ).css( 'visibility', 'hidden' );
            },
            success: function (response ) {

            }
        });
    });

   });