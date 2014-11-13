<?php
/**
 * User: nixx
 * Date: 11.11.14
 * Time: 15:35
 */
defined('_JEXEC') or die;

?>
<div id="rees46_popular"></div>
<div id="rees46_interesting"></div>
<script src="//cdn.rees46.com/rees46_script2.js"></script>
<script>
	REES46.init('<?= $params->get('rees46_shop_id', '') ?>', {}, function(){
		REES46.addStyleToPage();
	});
	jQuery('form.product input[name="virtuemart_product_id[]"]').each(function(){
		var self = this;
		REES46.addReadyListener(function () {
			REES46.pushData('view', {
				item_id: self.value,
				price: parseFloat(jQuery('span.PricesalesPrice').text()),
				is_available: '1'
			});
		});
	});

	function rees46_recommended(name, title) {
		REES46.addReadyListener(function () {
			REES46.recommend({
				recommender_type: name
			}, function(r){
				if( r.length ) {
					jQuery.ajax({
						url: '/modules/mod_rees46/goods.php',
						data: {
							id: r.join(',')
						},
						success: function(r) {
							jQuery('#rees46_' + name).html(r).find('.recommender-block-title').text(title);
						}
					});
				}
			});
		});
	}

	jQuery(function(){
		rees46_recommended('popular', 'Популярные товары');
		rees46_recommended('interesting', 'Возможно, вам это будет интересно');
	})
</script>
