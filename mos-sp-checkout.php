<?php
/**
 * Plugin Name:       Alpha Single Page Checkout
 * Plugin URI:        http://www.mdmostakshahid.com/
 * Description:       Base of future plugin
 * Version:           0.0.6
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
require_once('plugins/update/plugin-update-checker.php');
$pluginInit = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/mostak-shahid/update/master/mos-sp-checkout.json',
	MOS_SP_CHECKOUT_FILE,
	'mos-sp-checkout'
);


function add_slug_body_class( $classes ) {
    global $post;
    if ( get_page_template_slug() == 'mos-sp-checkout-template.php' ) {
        $classes[] = 'woocommerce-checkout woocommerce-page woocommerce-block-theme-has-button-styles woocommerce-js';
    }
    //$classes[] = "theme-default";
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

add_action('wp', 'pk_custom_checkout_wp');
function pk_custom_checkout_wp() {
	if ( basename(get_page_template_slug() ) == 'mos-sp-checkout-template.php' ) {
		if(!defined('WOOCOMMERCE_CART')) { define('WOOCOMMERCE_CART', true); }
		add_filter('woocommerce_is_checkout', '__return_true');
	}
}

//Load template from specific page
add_filter( 'page_template', 'mos_sp_checkout_page_template' );
function mos_sp_checkout_page_template( $page_template ){

    if ( basename(get_page_template_slug() ) == 'mos-sp-checkout-template.php' ) {
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
            )),
            Field::make( 'select', 'mos_sp_checkout_add_type', 'How product will be added to cart?' )
            ->add_options( array(
                'all' => 'Add all to cart',
                'switch' => 'Switch between products',
                //'together' => 'Buy together',
            ) ),
            Field::make( 'select', 'mos_sp_checkout_page_type', 'How page will be displayed?' )
            ->add_options( array(
                'default' => 'Default',
                'template-1' => 'Template 1',
            ) ),
            Field::make( 'rich_text', 'mos_sp_checkout_before_content', 'Before Content' )
                ->set_conditional_logic(array(
                    'relation' => 'AND', // Optional, defaults to "AND"
                    array(
                        'field' => 'mos_sp_checkout_page_type',
                        'value' => 'default', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '!=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    )
                )),
            Field::make( 'text', 'mos_sp_checkout_iframe', 'Iframe' )
                ->set_attribute( 'type', 'url' )
                ->set_conditional_logic(array(
                    'relation' => 'AND', // Optional, defaults to "AND"
                    array(
                        'field' => 'mos_sp_checkout_page_type',
                        'value' => 'default', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '!=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    )
                )),
            Field::make( 'select', 'mos_sp_checkout_iframe_ratio', 'Iframe Ration' )
                ->set_conditional_logic(array(
                    'relation' => 'AND', // Optional, defaults to "AND"
                    array(
                        'field' => 'mos_sp_checkout_iframe',
                        'value' => '', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '!=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    )
                ))
                ->add_options( array(
                    'ratio-1x1' => '1x1',
                    'ratio-4x3' => '4x3',
                    'ratio-16x9' => '16x9',
                    'ratio-21x9' => '21x9',
            ) ),
            Field::make( 'rich_text', 'mos_sp_checkout_after_content', 'After Content' )
            ->set_conditional_logic(array(
                'relation' => 'AND', // Optional, defaults to "AND"
                array(
                    'field' => 'mos_sp_checkout_page_type',
                    'value' => 'default', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                    'compare' => '!=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                )
            )),
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
    $mos_sp_checkout_add_type = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_add_type' );
    // var_dump($mos_sp_checkout_add_type);
    // die();
	if ( is_admin() ){
		return;
	}
    if ($mos_sp_checkout_products && sizeof($mos_sp_checkout_products)) {
        
        // $crrent_cart_ids = [];
        // $cart_items = $cart = WC()->cart->get_cart();
        // foreach( $cart as $cart_item_key => $cart_item ){
        //     $cart_product = $cart_item['data'];
        //     $crrent_cart_ids[] = $cart_product->get_id();
        // }

        if ($mos_sp_checkout_add_type == 'all' ){
            WC()->cart->empty_cart();
            foreach($mos_sp_checkout_products as $p) {
                WC()->cart->add_to_cart( $p['id'] );
            }
        } elseif($mos_sp_checkout_add_type == 'switch' ){
            WC()->cart->empty_cart();
            WC()->cart->add_to_cart( (@$_GET['p_id'])?$_GET['p_id']:$mos_sp_checkout_products[0]['id'] );
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
function mos_sp_checkout_enqueue_scripts(){
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'mos-sp-checkout-ajax', plugins_url( 'js/mos-sp-checkout-ajax.js', __FILE__ ), array('jquery') );
	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
	);
	wp_localize_script( 'mos-sp-checkout-ajax', 'mos_sp_checkout_ajax_obj', $ajax_params );

	wp_enqueue_script( 'jquery.validate.min', 'https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js', array('jquery') );    
	wp_enqueue_style( 'mos-sp-checkout', plugins_url( 'css/mos-sp-checkout.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'mos_sp_checkout_enqueue_scripts' );


function mos_sp_checkout_footer_script_function() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('.validate-required').find('input, select').attr('required', true);
            $(".checkout-form-wrap .woocommerce-checkout").validate();
        });
    </script>
	<style>
	.error {
		border: none;
		color: #E01020;
	}
	</style>
    <?php
}
add_action('wp_footer', 'mos_sp_checkout_footer_script_function');

function mos_sp_checkout_view_products(){
    global $post;
    $mos_sp_checkout_products = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_products' );
    $mos_sp_checkout_add_type = carbon_get_post_meta( $post->ID, 'mos_sp_checkout_add_type' );
    if (basename(get_page_template()) == 'mos-sp-checkout-template.php' && $mos_sp_checkout_add_type != 'all' && sizeof($mos_sp_checkout_products) > 1) : 
        ?>
        <div id="mos-sp-checkout-products-form-wrap">
        <?php 
            $crrent_cart_ids = [];
            $cart_items = $cart = WC()->cart->get_cart();
            foreach( $cart as $cart_item_key => $cart_item ){
                $cart_product = $cart_item['data'];
                $crrent_cart_ids[] = $cart_product->get_id();
            }
            //$current_id = (@$_GET['p_id'])?$_GET['p_id']:$mos_sp_checkout_products[0]['id'];
        ?>
        <?php foreach($mos_sp_checkout_products as $key => $value) :
            $_product = wc_get_product( $value['id'] );
            ?>
            <a href="?p_id=<?php echo $value['id'] ?>#mos-sp-checkout-products-form-wrap" class="mos-sp-checkout-product-form <?php echo (in_array($value['id'], $crrent_cart_ids))?'checked':'' ?>" data-id="<?php echo $value['id'] ?>" data-page_id="<?php echo get_the_ID() ?>">
                <span class="mos-sp-checkout-indicator"></span>
                <span class="mos-sp-checkout-text-wrapper">
                    <span class="mos-sp-checkout-product-title"><?php echo $_product->get_name(); ?></span>
                    <span class="mos-sp-checkout-price"><?php echo $_product->get_price_html(); ?></span>
                </span>
            </a>
        <?php endforeach;?>         
        </div>
    <?php endif;
}
add_action('woocommerce_after_checkout_billing_form', 'mos_sp_checkout_view_products');

/* AJAX action callback */
add_action( 'wp_ajax_mos_order_modify', 'mos_order_modify_ajax_callback' );
add_action( 'wp_ajax_nopriv_mos_order_modify', 'mos_order_modify_ajax_callback' );
/* Ajax Callback */
function mos_order_modify_ajax_callback () {
    $p_id = $_POST['p_id'];

    $mos_sp_checkout_products = carbon_get_post_meta( $_POST['page_id'], 'mos_sp_checkout_products' );
    $mos_sp_checkout_add_type = carbon_get_post_meta( $_POST['page_id'], 'mos_sp_checkout_add_type' );

    if($mos_sp_checkout_add_type == 'switch' ){
        WC()->cart->empty_cart();
        WC()->cart->add_to_cart( $p_id );
    }
    ob_start(); ?>

	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="product-total">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>

    <?php $html = ob_get_clean();
    $output = array('product_id' => $p_id, 'products' => $mos_sp_checkout_products, 'add_type' => $mos_sp_checkout_add_type, 'html'=>$html);
	echo json_encode($output);
    exit; // required. to end AJAX request.
}

