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
$header_layout = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_header' );
$header_layout_id = @$header_layout[0]['id'];
if ($header_layout_id) : 
?>
	<header class="mos-sg-checkout-header">
		<div class="wp-block-nk-awb nk-awb alignfull p-0"> 
			<?php
				$header_layout_content_post = get_post($header_layout_id);
				$header_layout_content = $header_layout_content_post->post_content;
				$header_layout_content = str_replace(']]>', ']]&gt;', $header_layout_content);
				echo $header_layout_content;  
			?>
		</div>
	</header>
<?php endif?>
<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained wp-block-post-content-is-layout-constrained">
<?php 
//var_dump(is_checkout());
$mos_sp_checkout_page_type = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_page_type' );
$mos_sp_checkout_before_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_before_content' );
$mos_sp_checkout_iframe = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe' );
$mos_sp_checkout_iframe_ratio = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_iframe_ratio' );
$mos_sp_checkout_after_content = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_after_content' );
$mos_sp_checkout_form_width = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_form_width' );

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
	// $page_content_post = get_post(get_the_ID());
	// $page_content = $page_content_post->post_content;
	// $page_content = str_replace(']]>', ']]&gt;', $page_content);
	// //echo $page_content; 
	echo get_the_content();
	
}
?>
</div>
<section class="checkout-form-wrap woocommerce" <?php echo ($mos_sp_checkout_form_width)?'style="max-width: '.$mos_sp_checkout_form_width.'px"':'' ?> >
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

<?php 
$footer_layout = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_footer' );
$footer_layout_id = @$footer_layout[0]['id'];
if ($footer_layout_id) : 
?>
	<footer class="mos-sg-checkout-footer">
		<div class="wp-block-nk-awb nk-awb alignfull p-0"> 
			<?php
				$footer_layout_content_post = get_post($footer_layout_id);
				$footer_layout_content = $footer_layout_content_post->post_content;
				$footer_layout_content = str_replace(']]>', ']]&gt;', $footer_layout_content);
				echo $footer_layout_content;  
			?>
		</div>
	</footer>
<?php endif?>
<?php wp_footer(); ?>
</body>
</html>

