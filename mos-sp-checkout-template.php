<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Md. Mostak Shahid">   
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php 
//var_dump(is_checkout());
$mos_sp_checkout_page_type = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_page_type' );
$mos_sp_checkout_before_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_before_content' );
$mos_sp_checkout_iframe = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe' );
$mos_sp_checkout_iframe_ratio = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe_ratio' );
$mos_sp_checkout_after_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_after_content' );

if ($mos_sp_checkout_page_type == 'template-1') {
	?>
	<section class="mos-sp-checkout-output">
	<?php
	if ($mos_sp_checkout_before_content) {
		?>
		<div class="before-content"><?php echo do_shortcode($mos_sp_checkout_before_content)?></div>
		<?php
	}
	if ($mos_sp_checkout_iframe) {
		?>
		<div class="iframe-content">
			<div class="ratio <?php echo ($mos_sp_checkout_iframe_ratio)?$mos_sp_checkout_iframe_ratio:'ratio-1x1' ?>">
				<iframe src="<?php echo do_shortcode($mos_sp_checkout_iframe)?>" allowfullscreen></iframe>
			</div>			
		</div>
		<?php
	}
	if ($mos_sp_checkout_after_content) {
		?>
		<div class="after-content"><?php echo do_shortcode($mos_sp_checkout_after_content)?></div>
		<?php
	}
	?>
	</section>
	<?php 
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
<?php wp_footer(); ?>
</body>
</html>
