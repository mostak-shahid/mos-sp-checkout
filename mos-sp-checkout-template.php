<?php get_header() ?>
<?php 
global $post;
$mos_sp_checkout_page_type = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_page_type' );
$mos_sp_checkout_before_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_before_content' );
$mos_sp_checkout_iframe = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe' );
$mos_sp_checkout_iframe_ratio = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe_ratio' );
$mos_sp_checkout_after_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_after_content' );

if ($mos_sp_checkout_page_type == 'template-1') {
	if ($mos_sp_checkout_before_content) {
		?>
		<section class="before-content"><?php echo do_shortcode($mos_sp_checkout_before_content)?></section>
		<?php
	}
	if ($mos_sp_checkout_iframe) {
		?>
		<section class="iframe-content">
			<div class="ratio <?php echo ($mos_sp_checkout_iframe_ratio)?$mos_sp_checkout_iframe_ratio:'ratio-1x1' ?>">
				<iframe src="<?php echo do_shortcode($mos_sp_checkout_iframe)?>" allowfullscreen></iframe>
			</div>			
		</section>
		<?php
	}
	if ($mos_sp_checkout_after_content) {
		?>
		<section class="after-content"><?php echo do_shortcode($mos_sp_checkout_after_content)?></section>
		<?php
	}
} else {
	the_content();
}
?>
<section class="checkout-form-wrap woocommerce">
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
