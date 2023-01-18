<?php 
/*
Template Name: Full image content hidden template
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(array('class' => 'mp_post portfolio-item has-text-align-'.$sectionalignment)); ?>>
	<a href="<?php echo esc_url(get_permalink()); ?>">
		<?php if ( $showfeaturedimg ) { ?>
			<div class="postthumbnail">
				<?php the_post_thumbnail( $imagesize, array('alt' => the_title_attribute( 'echo=0' ))); ?>
			</div>
		<?php } ?>
			<div class="excerpt <?php if ( $showreadmore ) { echo 'excerptreadmore'; } ?>"><?php
				if ( $showtitle ) { the_title( '<'.esc_attr($titletag).' class="mp_post_title">', '</'.esc_attr($titletag).'>' );  }
				
				if ($showcategoriesonpost){ 
					$terms = wp_get_post_terms( get_the_ID(), $taxonomyselected);
					echo '<div class="mp_post_terms">';
					foreach ($terms as $term) { echo '<span class="mp_post_single_term single_term_'.$term->term_id.'" >'.__( $term->name ).'</span>'; }
					echo '</div>';
				} ?>
				<?php if ( $showdate ) { ?><div class="date"><?php echo get_the_date(); ?></div><?php } ?>
				<?php if ( $showcontent ) { ?>
					<?php mpcustomblocks_portfolio_the_excerpt_maxcharlength($contentlength); 
				} ?>
				<?php if ( $showreadmore) { ?><span class="readmore"><?php echo $textreadmore; ?></span><?php } ?>
			</div>
	</a>
</div><?php 	
?>