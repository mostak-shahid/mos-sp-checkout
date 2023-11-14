<?php
function mos_sp_checkout_layout_post_types() {
	/**
	 * Post Type: Layouts.
	 */
	$labels = [
		"name" => esc_html__( "Layouts", "mos-sp-checkout" ),
		"singular_name" => esc_html__( "Layout", "mos-sp-checkout" ),
	];
	$args = [
		"label" => esc_html__( "Layouts", "mos-sp-checkout" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "layout", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
        "menu_icon" => "dashicons-editor-kitchensink",
        "menu_position" => 5,
	];
	register_post_type( "layout", $args );
}
add_action( 'init', 'mos_sp_checkout_layout_post_types' );
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
