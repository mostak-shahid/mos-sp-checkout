<?php get_header() ?>
<?php 
the_content();
?>
<section class="checkout-form-wrap">
<?php
/**
 * Detect plugin. For frontend only.
 */
include_once ABSPATH . 'wp-admin/includes/plugin.php';

// check for plugin using plugin name
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	echo do_shortcode('[woocommerce_checkout]');
} 
?>
</section>
<?php get_footer() ?>
