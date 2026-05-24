/**
 * @package 	WordPress
 * @subpackage 	Welfare
 * @version		1.1.8
 * 
 * Woocommerce Scripts
 * Created by CMSMasters
 * 
 */


"use strict";

jQuery(document).ready(function () { 
	cmsms_ajax_add_to_cart();
	
	
	jQuery('body').bind('added_to_cart', update_dynamic_cart);
	
	
	jQuery(window).scroll(function () { 
		if (jQuery(this).scrollTop() + jQuery(this).height() > jQuery('#main').offset().top + jQuery('#main').height()) {
			jQuery('.woocommerce-store-notice').css({'position' : 'relative'});
		} else {
			jQuery('.woocommerce-store-notice').css({'position' : 'fixed'});
		}
	} );
} );


var cmsms_added_product = {};

function cmsms_ajax_add_to_cart() {
	jQuery('.cmsms_add_to_cart_button').bind('click', function() {	
		var productInfo = jQuery(this).parents('.product_inner'), 
			productAmount = productInfo.find('.price > .amount, .price > ins > .amount').text(), 
			addedToCart = jQuery(this).parents('.cmsms_product_footer').find('.added_to_cart'), 
			product = {};
		
		
		product.name = productInfo.find('.cmsms_product_title a').text();
		
		product.price = productAmount.replace(cmsms_woo_script.currency_symbol, '');
		
		product.image = productInfo.find('figure img');
		
		
		addedToCart.addClass('cmsms_to_show');
		
		
		if (product.image.length) {
			/* Dynamic Cart Update Img Src */
			var str = product.image.get(0).src, 
				ext = /(\..{3,4})$/i.exec(str), 
				extLength = ext[1].length, 
				url = str.slice(0, -extLength), 
				newURL = /(-\d{2,}x\d{2,})$/i.exec(url), 
				newSize = '-' + cmsms_woo_script.thumbnail_image_width + 'x' + cmsms_woo_script.thumbnail_image_height, 
				buildURL = '';
			
			
			if (newURL !== null) {
				buildURL += url.slice(0, -newURL[1].length) + newSize + ext[1];
			} else {
				buildURL += url + ext[1];
			}
			
			
			product.image = '<img class="cmsms_added_product_info_img" src="' + buildURL + '" />';
		}
		
		
		cmsms_added_product = product;
	} );
}


function update_dynamic_cart(event) { 
	var product = jQuery.extend( { 
		name : 		'', 
		price : 		'', 
		image : 	'' 
	}, cmsms_added_product);
	
	
	if (typeof event != 'undefined') {
		var cart_button = jQuery('.cmsms_dynamic_cart .cmsms_dynamic_cart_button'), 
			template = jQuery( 
				'<div class="cmsms_added_product_info">' + 
					product.image + 
					'<span class="cmsms_added_product_info_text">' + product.name + '</span>' + 
				'</div>' 
			);
		
		
		jQuery(event.currentTarget).find('a.cmsms_to_show').removeClass('cmsms_to_show').addClass('cmsms_visible');
		
		
		template.appendTo('.cmsms_dynamic_cart').css('visibility', 'visible').animate( { 
			top : 		0, 
			opacity : 	1 
		}, 500);
		
		
		template.bind('mouseenter cmsms_hide', function () { 
			template.animate( { 
				top : 		'-30px', 
				opacity : 	0 
			}, 500, function () { 
				template.remove();
			} );
		} );
		
		
		setTimeout(function () { 
			template.trigger('cmsms_hide');
		}, 2000);
	}
}


jQuery(document).ready(function() {
	(function ($) {
		$('.touch .product .product_inner figure').bind('click', function() { 
			$('*:not(this)').removeClass('cmsms_mobile_hover');
			
			
			$(this).addClass('cmsms_mobile_hover');
		} );
		
		
		$('.cmsms_woo_tabs .description_tab').addClass('current_tab');
		
		$('.cmsms_woo_tabs #tab-description').addClass('active_tab');
	} )(jQuery);
} );

