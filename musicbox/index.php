<?php

/**
 * Plugin Name: Gutenberg Music Box
 * Description: This is a plugin to show music releases in Gutenberg Blocks
 * Version: 1.0
 * Author: Matthieu Pain
 *
 * @package mp-custom-blocks
 */

defined( 'ABSPATH' ) || exit;


/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function gutenberg_musicbox_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'gutenberg-musicbox',
		plugins_url( 'block.js', __FILE__ ),
		// Dépendances
		array( 'wp-blocks', 'wp-i18n', 'wp-components', 'wp-element', 'wp-editor', 'underscore' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	wp_register_style('mp_box_style', plugins_url( '/musicbox/style.css', dirname(__FILE__) ), array( ));
	
	// Style appliqué à Gutenberg uniquement
	wp_register_style('mp_box_editor_style',plugins_url( '/musicbox/editor.css', dirname(__FILE__) ), array( 'wp-edit-blocks' ));

	register_block_type( 'gutenberg-musicbox/my-musicbox', array(
		'editor_style' => 'mp_box_editor_style',
		'style' => 'mp_box_style',
		'editor_script' => 'gutenberg-musicbox',
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'gutenberg-musicbox', 'mpcustomblocks' );
  }

}
add_action( 'init', 'gutenberg_musicbox_register_block' );