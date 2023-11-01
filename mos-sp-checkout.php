<?php
/**
 * Plugin Name:       Mos Single Page Checkout
 * Plugin URI:        http://www.mdmostakshahid.com/
 * Description:       Base of future plugin
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Md. Mostak Shahid
 * Author URI:        http://www.mdmostakshahid.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        http://www.mdmostakshahid.com/
 * Text Domain:       mos-form-pdf
 * Domain Path:       /languages
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MOS_SP_CHECKOUT_FILE.
if ( ! defined( 'MOS_SP_CHECKOUT_FILE' ) ) {
	define( 'MOS_SP_CHECKOUT_FILE', __FILE__ );
}

//Load template from specific page
add_filter( 'page_template', 'mos_sp_checkout_page_template' );
function mos_sp_checkout_page_template( $page_template ){

    if ( get_page_template_slug() == 'mos-sp-checkout-template.php' ) {
        $page_template = dirname( __FILE__ ) . '/mos-sp-checkout-template.php';
    }
    return $page_template;
}

/**
 * Add "Custom" template to page attirbute template section.
 */
add_filter( 'theme_page_templates', 'mos_sp_checkout_add_template_to_select', 10, 4 );
function mos_sp_checkout_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

    // Add custom template named template-custom.php to select dropdown 
    $post_templates['mos-sp-checkout-template.php'] = __('Checkout template');

    return $post_templates;
}


use Carbon_Fields\Container;
use Carbon_Fields\Field;
add_action('carbon_fields_register_fields', 'mos_sp_checkout_post_meta_options');

function mos_sp_checkout_post_meta_options() {
    $post_id = (@$_GET['post'])?$_GET['post']:0;
    //if ( $post_id && 'mos-sp-checkout-template.php' == get_post_meta( $post_id, '_wp_page_template', true ) ) {
        Container::make('post_meta', 'Checkout Page Data')
        ->where('post_type', '=', 'page')
        ->show_on_template('mos-sp-checkout-template.php')
        ->add_fields(array(
            Field::make( 'association', 'mos_sp_checkout_products', __( 'Select Products' ) )
            //->set_required( true )
            ->set_types( array(
                array(
                    'type'      => 'post',
                    'post_type' => 'product',
                )
            ))
        ));  
    //}
}

//add_action('after_setup_theme', 'crb_load');
add_action('after_setup_theme','mos_sp_checkout_crb_load');
function mos_sp_checkout_crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}

add_action( 'template_redirect', 'mos_remove_product_from_cart_programmatically' );
function mos_remove_product_from_cart_programmatically() {
    global $post;
    $mos_sp_checkout_products = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_products' );
    // var_dump($mos_sp_checkout_products);
    // die();
	if ( is_admin() ){
		return;
	}
    if ($mos_sp_checkout_products && sizeof($mos_sp_checkout_products)) {
        WC()->cart->empty_cart();
        foreach($mos_sp_checkout_products as $p) {
            WC()->cart->add_to_cart( $p['id'] );
        }
    }
	// $product_id = (@$_GET['product_id'])?$_GET['product_id']:0; // Product ID
    // if (@$product_id) {
    //     WC()->cart->empty_cart(); //<- This just empty all the products in the cart.
    //     WC()->cart->add_to_cart( $product_id );
    //     $product_cart_id = WC()->cart->generate_cart_id( $product_id );
    //     $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
    //     if ( $cart_item_key ) {
    //     	WC()->cart->remove_cart_item( $cart_item_key );
    //     }
    // }
}
