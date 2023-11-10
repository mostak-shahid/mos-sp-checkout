jQuery(document).ready(function($){	
    $('.mos-sp-checkout-product-form').on('click', function(e){
        e.preventDefault();
        var ths = $(this);
        var p_id = $(this).data('id');
        var page_id = $(this).data('page_id');
        if (p_id) {
            $('.checkout-form-wrap').append('<div class="mos-sp-checkout-loading">Loading...</div>');
            $.ajax({
                url: mos_sp_checkout_ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
                type:"POST",
                dataType:"json",
                data: {
                    'action': 'mos_order_modify',
                    'p_id' : p_id,
                    'page_id' : page_id,
                },
                success: function(result){
                    //console.log(result); 
                    $('.woocommerce-checkout-review-order-table').html(result.html);
                    ths.addClass('checked');
                    ths.siblings().removeClass('checked');
                    $('.mos-sp-checkout-loading').remove();
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
    });
    //$('body').on('click', '.projects-wrapper .pagination-wrapper .page-numbers', function (e){
    
	/*$('.track-form').submit(function(e){
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);
        $.ajax({
            url: mos_ajax_object.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'order_tracking',
                'form_data' : form_data,
            },
            success: function(result){
                // console.log(result);
                $('.track-output').html(result);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
	});*/
});