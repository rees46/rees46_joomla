<?php
/**
 * User: nixx
 * Date: 11.11.14
 * Time: 15:35
 */
defined('_JEXEC') or die;

?>
<script>
	function initREES46() {
		REES46.init('<?= $params->get('rees46_shop_id', '') ?>', {}, function(){
			REES46.addStyleToPage();
		});
		if( jQuery('.productdetails').length ) {
			jQuery('form.product input[name="virtuemart_product_id[]"]').each(function() {
				var self = this;
				REES46.addReadyListener(function() {
					var categories = jQuery('.rees46-recommend-product-categories').attr('data-id');
					REES46.pushData('view', {
						item_id: self.value,
						price: parseFloat(jQuery('span.PricesalesPrice').text()),
						categories: categories ? categories.split(',') : undefined,
						name: jQuery('meta[name="title"]').attr('content'),
						is_available: '1'
					});
				});
			});
		}

		var recommender_titles = {
			interesting: 'Вам это будет интересно',
			also_bought: 'С этим также покупают',
			similar: 'Похожие товары',
			popular: 'Популярные товары',
			see_also: 'Посмотрите также',
			recently_viewed: 'Вы недавно смотрели'
		};
		jQuery('.rees46-recommend').each(function(){
			var id = this.id.replace(/^rees46_/, ''), cart = jQuery(this).attr('data-cart');
			rees46_recommended({
				recommender_type: id,
				item: jQuery(this).attr('data-id'),
				category: jQuery(this).attr('data-category'),
				cart: cart ? cart.split(',') : []
			}, recommender_titles[id]);
		});
	}

	function rees46_recommended(v, title) {
		REES46.addReadyListener(function () {
			REES46.recommend(v, function(r){
				if( r.length ) {
					jQuery.ajax({
						url: '/modules/mod_rees46/goods.php',
						data: {
							id: r.join(','),
							recommended_by: v.recommender_type
						},
						success: function(r) {
							jQuery('#rees46_' + v.recommender_type).html(r).find('.recommender-block-title').text(title);
						}
					});
				}
			});
		});
	}

	jQuery(function(){
		var script = document.createElement('script');
		script.src = '//cdn.rees46.com/rees46_script2.js';
		script.async = true;
		script.onload = function() {
			initREES46();
		};
		document.getElementsByTagName('head')[0].appendChild(script);
	})
</script>
