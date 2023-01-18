<?php

/**
 * Plugin Name: Gutenberg Background Image
 * Description: This is a plugin to show responsive background images with correct sizes vor the viewport
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
function gutenberg_bgimage_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'gutenberg-bgimage',
		plugins_url( 'block.js', __FILE__ ),
		// Dépendances
		array( 'wp-blocks', 'wp-i18n', 'wp-components', 'wp-element', 'wp-editor', 'underscore' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	wp_register_style('mp_backgroundimg_style', plugins_url( '/backgroundimg/style.css', dirname(__FILE__) ), array( ));
	
	// Style appliqué à Gutenberg uniquement
	wp_register_style('mp_backgroundimg_editor_style',plugins_url( '/backgroundimg/editor.css', dirname(__FILE__) ), array( 'wp-edit-blocks' ));

	register_block_type( 'gutenberg-bgimage/my-bgimage', array(
		'editor_style' => 'mp_backgroundimg_editor_style',
		'style' => 'mp_backgroundimg_style',
		'editor_script' => 'gutenberg-bgimage',
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'gutenberg-mbgimage', 'mpcustomblocks' );
  }

}
add_action( 'init', 'gutenberg_bgimage_register_block' );

/* Fonction pour récupérer les urls des background images à la bonne taille */
function mp_responsive_background_img( $content ) {
	if ( ! preg_match_all( '/has-background-image-(\d+)/', $content, $matches, PREG_PATTERN_ORDER ) ) {
		return $content;
	}

	$styles = array();

	$attachment_ids = array_unique( array_map( 'absint', $matches[1] ) );
	foreach ( $attachment_ids as $attachment_id ) {
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( ! $meta ) {
			continue;
		}

		$attachment_url = wp_get_attachment_url( $attachment_id );
		$base_url       = str_replace( wp_basename( $attachment_url ), '', $attachment_url );

		if ( empty( $meta['sizes'] ) ) {
			$styles[] = "
	.has-background-image-{$attachment_id} {
		background-image: url('{$attachment_url}');
	}";
			continue;
		}

		$sizes = wp_list_sort( $meta['sizes'], 'width', 'ASC', true );
		if ( ! isset( $sizes['full'] ) ) {
			$sizes['full'] = array( 'url' => $attachment_url, 'width' => $meta['width'] );
		}
		$sizes = array_values( $sizes );

		$style            = array();
		$widths           = array();
		$min_width        = 0;
		$min_width_retina = 0;
		$size_count       = count( $sizes );
		foreach ( $sizes as $index => $size_meta ) {
			if ( $size_meta['width'] < 480 || in_array( $size_meta['width'], $widths, true ) ) {
				continue;
			}

			$widths[] = $size_meta['width'];

			if ( $index === $size_count - 1 ) {
				// Do not specify max-width for the largest available width.
				$max_width        = 0;
				$max_width_retina = 0;
			} else {
				$max_width        = $size_meta['width'];
				$max_width_retina = $size_meta['width'] / 2;
			}

			$media_query        = mp_get_media_query( $min_width, $max_width );
			$media_query_retina = mp_get_media_query_retina( $min_width_retina, $max_width_retina );

			$size_url = ! empty( $size_meta['url'] ) ? $size_meta['url'] : $base_url . $size_meta['file'];

			$style[] = "
	@media {$media_query} {
		.has-background-image-{$attachment_id} {
			background-image: url('{$size_url}');
		}
	}
	@media {$media_query_retina} {
		.has-background-image-{$attachment_id} {
			background-image: url('{$size_url}');
		}
	}";

			$min_width        = $max_width + 1;
			$min_width_retina = $max_width_retina + 1;
		}

		$styles[] = implode( '', $style );
	}

	$content = '<style type="text/css">' . implode( '', $styles ) . '</style>' . $content;

	return $content;
}
add_filter( 'the_content', 'mp_responsive_background_img', 100 );

function mp_get_media_query( $min_width, $max_width ) {
	if ( $min_width && $max_width ) {
		return "screen and (min-width: {$min_width}px) and (max-width: {$max_width}px)";
	}
	if ( $min_width ) {
		return "screen and (min-width: {$min_width}px)";
	}
	if ( $max_width ) {
		return "screen and (max-width: {$max_width}px)";
	}
	return '';
}
function mp_get_media_query_retina( $min_width, $max_width ) {
	if ( $min_width && $max_width ) {
		return "screen and (-webkit-min-device-pixel-ratio: 2) and (min-width: {$min_width}px) and (max-width: {$max_width}px),
		screen and (min-resolution: 192dpi) and (min-width: {$min_width}px) and (max-width: {$max_width}px),
		screen and (min-resolution: 2dppx) and (min-width: {$min_width}px) and (max-width: {$max_width}px)";
	}
	if ( $min_width ) {
		return "screen and (-webkit-min-device-pixel-ratio: 2) and (min-width: {$min_width}px),
		screen and (min-resolution: 192dpi) and (min-width: {$min_width}px),
		screen and (min-resolution: 2dppx) and (min-width: {$min_width}px)";
	}
	if ( $max_width ) {
		return "screen and (-webkit-min-device-pixel-ratio: 2) and (max-width: {$max_width}px),
		screen and (min-resolution: 192dpi) and (max-width: {$max_width}px),
		screen and (min-resolution: 2dppx) and (max-width: {$max_width}px)";
	}
	return '';
}