<?php

/**
 * Plugin Name: MP Custom Blocks
 * Plugin URI: https://vvv.mattpain.com
 * Description: Custom blocks for the Gutenberg editor.
 * Version: 1.0
 * Author: Matthieu Pain
 * Author URI: https://vvv.mattpain.com
 *
 * Text Domain: mpcustomblocks
 * Domain Path: /languages/
 *
 * @package mp-custom-blocks
 */

defined( 'ABSPATH' ) || exit;

include 'portfolio/index.php';
include 'musicbox/index.php';
include 'backgroundimg/index.php';

/**
 * Load all translations for our plugin from the MO file.
*/
function gutenberg_mpcustomblocks_load_textdomain() {
	load_plugin_textdomain( 'mpcustomblocks', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'init', 'gutenberg_mpcustomblocks_load_textdomain' );

/**
 * Creat specific category for MP Custom Blocks
*/
function mpcustomblocks_block_categories( $categories ) {
    $category_slugs = wp_list_pluck( $categories, 'slug' );
    return in_array( 'mpcustomblocks', $category_slugs, true ) ? $categories : array_merge(
        $categories,
        array(
            array(
                'slug'  => 'mpcustomblocks',
                'title' => __( 'MP Custom Blocks', 'mpcustomblocks' ),
                'icon'  => null,
            ),
        )
    );
}
add_filter( 'block_categories', 'mpcustomblocks_block_categories' );
