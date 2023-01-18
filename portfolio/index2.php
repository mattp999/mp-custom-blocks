<?php

/**
 * Plugin Name: Gutenberg Portfolio
 * Description: This is a plugin to show a specific Post type in Portfolio in Gutenberg Blocks
 * Version: 1.0
 * Author: Matthieu Pain
 *
 * @package mp-custom-blocks
 */

defined( 'ABSPATH' ) || exit;

function gutenberg_portfolio_render_callback( $attributes ) {

		$query_args = array(
			'post_type' => $attributes['postType'],
			'posts_per_page'   => $attributes['postsToShow'],
			'ignore_sticky_posts' => 1,
			'post_status'      => 'publish',
			'order'            => $attributes['order'],
			'orderby'          => $attributes['orderBy'],
			'suppress_filters' => '0',
		);
		
		// if ( isset( $attributes['showTerms']) && isset( $attributes['termSelected']) ) {
			// $query_args['tax_query'] = array(
				// array(
					// 'taxonomy' => $attributes['termSelected'], //'category',
					// 'field' => 'id',
					// 'terms' => $attributes['showTerms']
				// )
			// );
		// }
	
	$block_name = 'mp_portfolio mp_portfolio_'.$attributes['postType'].' ';
	
    $extra_attr = array(
        'block_name' => $block_name
    );

    $class = $block_name;
	
	$taxonomySelected = $attributes['taxonomySelected'];	
	$showTaxonomiesList = $attributes['showTaxonomiesList'];	
	$alignment = $attributes['alignment'];
	$showDate = isset( $attributes['showDate'] ) && $attributes['showDate'];
	$showTitle = isset( $attributes['showTitle'] ) && $attributes['showTitle'];
	$showFeaturedImg = isset( $attributes['showFeaturedImg'] ) && $attributes['showFeaturedImg'];
	$imageSize = ( ( isset($attributes['imageSize']) && $attributes['imageSize'] ) ? $attributes['imageSize'] : 'post-thumbnail');
	$showContent = isset($attributes['showContent']) && $attributes['showContent'];
	$contentLength = $attributes['contentLength'];
	$showReadMore = isset($attributes['showReadMore']) && $attributes['showReadMore'];
	$showLoadMore = isset($attributes['showLoadMore']) && $attributes['showLoadMore'];
	
	
    $query = new WP_Query( $query_args );

    ob_start();
    ?>    

    <div class="<?php echo esc_attr( $class ); ?>">
        
		<?php if ( $query->have_posts() ): ?>
		
		<?php if ( $showLoadMore ) { 
		
		$maxnumpages = $query->max_num_pages; ?>
			<!-- Ajax paramètres Load more pour ce post type spécifique (si 2 blocs portfoliosont sur la même page -->
			<script>
				var posts_ajax<?php echo $attributes['postType'] ?> = '<?php echo serialize( $query->query_vars ) ?>',
				current_page_ajax<?php echo $attributes['postType'] ?> = 1,
				max_page_ajax<?php echo $attributes['postType'] ?> = <?php echo $maxnumpages ?>
			</script>
		<?php } ?>
		
		<?php // Liste catégories
		if ($showTaxonomiesList && $attributes['postType'] !== 'post'){
			$terms = get_terms($taxonomySelected);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				echo '<ul>';
				echo '<li id="100" class="cat active">'.__( 'All', 'mpcustomblocks' ).'</li>';
				foreach ( $terms as $term ) {
					echo '<li id="'.$term->term_id.'" class="cat">'.__( $term->name ).'</li>';
				}
				echo '</ul>';
			} 
		}
		// Liste catégories Post
		if ($showTaxonomiesList && $attributes['postType'] === 'post'){
			$terms = get_categories($taxonomySelected);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				echo '<ul>';
				echo '<li id="100" class="cat active">'.__( 'All', 'mpcustomblocks' ).'</li>';
				foreach ( $terms as $term ) {
					echo '<li id="'.$term->term_id.'" class="cat">'.__( $term->name ).'</li>';
				}
				echo '</ul>';
			} 
		}?>	


			<div class="container-animation">
			
				<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="44px" height="44px" viewBox="0 0 128 128" xml:space="preserve">
					<g><path fill="<?php echo $attributes['colorAnimation'] ?>" d="M26.9 65.08c3.87 21.1 21.26 37 42.13 37 23.72 0 41.6-20.58 42.95-45.88 1-18.84-9.45-37.5-32.57-47.88A52.2 52.2 0 0 0 47.08 5c22.23-6.02 41.53.02 54.6 10.66 8.2 6.46 16.12 15.33 19.32 24.4a67.13 67.13 0 0 1 3.77 19.85c0 34.4-26.87 62.3-61.26 62.3A62.27 62.27 0 0 1 2.05 70.1c.57-15.82 19.83-18.23 24.83-5.02z"/>
					<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform></g>
				</svg>
		
			</div>
			<div class="newswrapper"
				data-alignment="<?php echo $attributes['alignment'];?>"
				data-posttype="<?php echo $attributes['postType'];?>"
				data-taxonomyselected="<?php echo $taxonomySelected;?>"
				data-poststoshow="<?php echo $attributes['postsToShow'];?>"
				data-order="<?php echo $attributes['order'];?>"
				data-orderby="<?php echo $attributes['orderBy'];?>"
				data-showdate="<?php echo $attributes['showDate'];?>"
				data-showtitle="<?php echo $attributes['showTitle'];?>"
				data-titletag="<?php echo $attributes['titleTag'];?>"
				data-showfeaturedimg="<?php echo $attributes['showFeaturedImg'];?>"
				data-imagesize="<?php echo $attributes['imageSize'];?>"
				data-showcontent="<?php echo $attributes['showContent'];?>"
				data-contentlength="<?php echo $attributes['contentLength'];?>"
				data-showreadmore="<?php echo $attributes['showReadMore'];?>"
				data-textreadmore="<?php echo $attributes['textReadMore'];?>"
				data-textloadmore="<?php echo $attributes['textLoadMore'];?>"
				data-textloading="<?php echo $attributes['textLoading'];?>"
				data-showloadmore="<?php echo $attributes['showLoadMore'];?>">
			
			<?php ob_start();
				while( $query->have_posts() ):
					$query->the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(array('class' => 'mp_post portfolio-item wow fadeInUp has-text-align-'.$alignment)); ?>>
						<a href="<?php echo esc_url(get_permalink()); ?>">
							<?php if ( $showFeaturedImg ) { ?>
								<div class="postthumbnail">
									<?php the_post_thumbnail( $imageSize, array('alt' => the_title_attribute( 'echo=0' ))); 
									if ( $showTitle ) {
										the_title( '<'.esc_attr($attributes['titleTag']).' class="mp_post_title">', '</'.esc_attr($attributes['titleTag']).'>' ); 
									}
									if ($attributes['postType'] === 'vod-dvd'){ 
										$terms = wp_get_post_terms( get_the_ID(), 'vod-dvd-types');
										foreach ($terms as $term) {
										  echo '<div class="categoryvod '.$term->term_id.'" >'.__( $term->name ).'</div>';
										}
									 } ?>
								</div>
							<?php }?>
							<div class="excerpt <?php if ( $showReadMore ) { echo 'excerptreadmore'; } ?>">
								<?php if ( $showDate ) { 
										if ($attributes['postType'] === 'realisations'){ ?>
											<div class="date"><?php echo get_the_date('Y'); ?></div><?php
										}else{ ?>
											 <div class="date"><?php echo get_the_date(); ?></div><?php
										}
									} ?>
								<?php if ( $showContent ) { ?>
									<?php mpcustomblocks_portfolio_the_excerpt_maxcharlength($contentLength); 
								} ?>
								<?php if ( $showReadMore ) { ?>
									<span class="readmore"><?php echo $attributes['textReadMore']; ?></span>
								<?php } ?>
							</div>
						</a>
					</div>
					
				<?php endwhile; ?>
				
				<?php //$post_type = get_post_type_object($attributes['postType'] ); 
					  //echo '<a class="buttoncat" href="/'.$post_type->rewrite['slug'].'">'.__( 'See all our', 'mpcustomblocks' ).' <span style="text-transform:lowercase;">'.$post_type->label.'</span></a>'; ?>
			
			</div>
			
			<?php if ( $showLoadMore ) { ?>
				<button class="mp_custom_blocks_loadmore_portfolio" <?php if ($maxnumpages < 2 ){echo 'style="visibility:hidden;"'; }?> ><span><?php echo $attributes['textLoadMore']; ?></span>
				<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" id="loadmorebuttonsvg" width="24px" height="24px" viewBox="0 0 128 128" xml:space="preserve">
					<g><path fill="<?php echo $attributes['colorAnimation'] ?>" d="M26.9 65.08c3.87 21.1 21.26 37 42.13 37 23.72 0 41.6-20.58 42.95-45.88 1-18.84-9.45-37.5-32.57-47.88A52.2 52.2 0 0 0 47.08 5c22.23-6.02 41.53.02 54.6 10.66 8.2 6.46 16.12 15.33 19.32 24.4a67.13 67.13 0 0 1 3.77 19.85c0 34.4-26.87 62.3-61.26 62.3A62.27 62.27 0 0 1 2.05 70.1c.57-15.82 19.83-18.23 24.83-5.02z"/>
					<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform></g>
				</svg>
				</button>			
			<?php } ?>
			
			<?php
			wp_reset_postdata();
			ob_end_flush();
		endif; ?>
        
    </div>

    <?php

    $result = ob_get_clean();
    return $result;
}


/******************************************************
*	 Affichage Posts AJAX CLIC CATEGORIE	   		  *
*******************************************************/
// Réponse au clic catégorie portfolio
add_action( 'wp_ajax_nopriv_reload_portfolio', 'reload_portfolio_response' );
add_action( 'wp_ajax_reload_portfolio', 'reload_portfolio_response' );
function reload_portfolio_response () {
    $cat_id = $_POST[ 'catID' ];
    $postType = $_POST[ 'postType' ];
    $taxonomySelected = $_POST[ 'taxonomySelected' ];
    $alignment = $_POST[ 'alignmenT' ];
    $postsToShow = $_POST[ 'postsToShow' ];
    $order = $_POST[ 'ordeR' ];
    $orderBy = $_POST[ 'orderBy' ];
    $showDate = $_POST[ 'showDate' ];
    $showTitle = $_POST[ 'showTitle' ];
    $titleTag = $_POST[ 'titleTag' ];
    $showFeaturedImg = $_POST[ 'showFeaturedImg' ];
    $imageSize = $_POST[ 'imageSize' ];
    $showContent = $_POST[ 'showContent' ];
    $contentLength = $_POST[ 'contentLength' ];
    $showReadMore = $_POST[ 'showReadMore' ];
    $textReadMore = $_POST[ 'textReadMore' ];
	$showLoadMore = $_POST['showLoadMore'];
	
	$query_args = array(
		'post_type' => $postType,
		'posts_per_page'   => $postsToShow,
		'ignore_sticky_posts' => 1,
		'post_status'      => 'publish',
		'order'            => $order,
		'orderby'          => $orderBy,
		'suppress_filters' => '0',
		'paged'          => $_POST['page'] + 1
	);	
	if ($cat_id != 100 && $postType !== 'post') {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomySelected,
				'field' => 'term_id',
				'terms' => array( $cat_id )
			)
		);
	}
	if ($cat_id != 100 && $postType === 'post') {
		$query_args = array(
			'post_type' => $postType,
			'posts_per_page'   => $postsToShow,
			'ignore_sticky_posts' => 1,
			'post_status'      => 'publish',
			'order'            => $order,
			'orderby'          => $orderBy,
			'suppress_filters' => '0',
			'paged'          => $_POST['page'] + 1,
			'cat' => $cat_id,
		);
	}
		
	$query = new WP_Query( $query_args );
	if ( $query->have_posts() ):
				
		ob_start();
		while( $query->have_posts() ):
			$query->the_post(); ?>
				
			<div id="post-<?php the_ID(); ?>" <?php post_class(array('class' => 'mp_post portfolio-item wow fadeInUp has-text-align-'.$alignment)); ?>>
				<a href="<?php echo esc_url(get_permalink()); ?>">
					<?php if ( $showFeaturedImg ) { ?>
						<div class="postthumbnail">
							<?php the_post_thumbnail( $imageSize, array('alt' => the_title_attribute( 'echo=0' ))); ?>
							<?php if ( $showTitle ) { ?>
								<?php the_title( '<'.esc_attr($titleTag).' class="mp_post_title">', '</'.esc_attr($titleTag).'>' ); ?>
							<?php } ?>
						</div>
					<?php }?>
					<div class="excerpt <?php if ( $showReadMore ) { echo 'excerptreadmore'; } ?>">
						<?php if ( $showDate ) { 
							if ($postType === 'realisations'){ ?>
								<div class="date"><?php echo get_the_date('Y'); ?></div><?php
							}else{ ?>
								 <div class="date"><?php echo get_the_date(); ?></div><?php
							}
						} ?>
						<?php if ( $showContent ) { ?>
							<?php mpcustomblocks_portfolio_the_excerpt_maxcharlength($contentLength); 
						} ?>
						<?php if ( $showReadMore ) { ?>
							<span class="readmore"><?php echo $textReadMore; ?></span>
						<?php } ?>
					</div>
				</a>
			</div>
		<?php endwhile; ?>
		
		
		<!-- Ajax Load more Params 2 -->
		<?php if ( $showLoadMore ) { ?>
			<script>
				var posts_ajax<?php echo $postType ?> = '<?php echo serialize( $query->query_vars ) ?>',
				current_page_ajax<?php echo $postType ?> = 1,
				max_page_ajax<?php echo $postType ?> = <?php echo $query->max_num_pages ?>
			</script>
		<?php } ?>
		<?php
		/*$post_type = get_post_type_object($postType); 
		if(get_term( $cat_id )->name){
			echo '<a class="buttoncat" href="/categorie/'.get_term( $cat_id )->slug.'">'.__( 'See all our', 'mpcustomblocks' ).' <span style="text-transform:lowercase;">'.$post_type->label.'</span> '.__( 'in', 'mpcustomblocks' ).' '.get_term( $cat_id )->name.'</a>';
		}
		else{
			echo '<a class="buttoncat" href="/'.$post_type->rewrite['slug'].'">'.__( 'See all our', 'mpcustomblocks' ).' <span style="text-transform:lowercase;">'.$post_type->label.'</span></a>'; 
		}*/
		
		wp_reset_postdata();
		ob_end_flush();
		wp_die();
	endif;
} 
/**/

/******************************************************
*  Affichage Posts AJAX CLIC Bouton "Charger +"  	  *
*******************************************************/
// Réponse au clic catégorie portfolio
add_action( 'wp_ajax_nopriv_loadmoreportfolioposts', 'reload_portfolio_loadmore' );
add_action( 'wp_ajax_loadmoreportfolioposts', 'reload_portfolio_loadmore' );
function reload_portfolio_loadmore() {
    $cat_id = $_POST[ 'catID' ];
    $postType = $_POST[ 'postType' ];
	$taxonomySelected = $_POST[ 'taxonomySelected' ];
    $alignment = $_POST[ 'alignmenT' ];
    $postsToShow = $_POST[ 'postsToShow' ];
    $order = $_POST[ 'ordeR' ];
    $orderBy = $_POST[ 'orderBy' ];
    $showDate = $_POST[ 'showDate' ];
    $showTitle = $_POST[ 'showTitle' ];
    $titleTag = $_POST[ 'titleTag' ];
    $showFeaturedImg = $_POST[ 'showFeaturedImg' ];
    $imageSize = $_POST[ 'imageSize' ];
    $showContent = $_POST[ 'showContent' ];
    $contentLength = $_POST[ 'contentLength' ];
    $showReadMore = $_POST[ 'showReadMore' ];
    $textReadMore = $_POST[ 'textReadMore' ];
	$showLoadMore = $_POST['showLoadMore'];
		
	$query_args = array(
		'post_type' => $postType,
		'posts_per_page'   => $postsToShow,
		'ignore_sticky_posts' => 1,
		'post_status'      => 'publish',
		'order'            => $order,
		'orderby'          => $orderBy,
		'suppress_filters' => '0',
		'paged'          => $_POST['page'] + 1
	);	
	if ($cat_id != 100 && $postType !== 'post') {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomySelected,
				'field' => 'term_id',
				'terms' => array( $cat_id )
			)
		);
	}
	if ($cat_id != 100 && $postType === 'post') {
		$query_args = array(
			'post_type' => $postType,
			'posts_per_page'   => $postsToShow,
			'ignore_sticky_posts' => 1,
			'post_status'      => 'publish',
			'order'            => $order,
			'orderby'          => $orderBy,
			'suppress_filters' => '0',
			'paged'          => $_POST['page'] + 1,
			'cat' => $cat_id,
		);
	}
	
	$query = new WP_Query( $query_args );
	
	if ( $query->have_posts() ):
				
		ob_start();
		while( $query->have_posts() ):
			$query->the_post(); ?>
			
			<div id="post-<?php the_ID(); ?>" <?php post_class(array('class' => 'mp_post portfolio-item wow fadeInUp has-text-align-'.$alignment)); ?>>
				<a href="<?php echo esc_url(get_permalink()); ?>">
					<?php if ( $showFeaturedImg ) { ?>
						<div class="postthumbnail">
							<?php the_post_thumbnail( $imageSize, array('alt' => the_title_attribute( 'echo=0' ))); ?>
							<?php if ( $showTitle ) { ?>
								<?php the_title( '<'.esc_attr($titleTag).' class="mp_post_title">', '</'.esc_attr($titleTag).'>' ); ?>
							<?php } ?>
						</div>
					<?php }?>
					<div class="excerpt <?php if ( $showReadMore ) { echo 'excerptreadmore'; } ?>">
						<?php if ( $showDate ) { 
							if ($postType === 'realisations'){ ?>
								<div class="date"><?php echo get_the_date('Y'); ?></div><?php
							}else{ ?>
								 <div class="date"><?php echo get_the_date(); ?></div><?php
							}
						} ?>
						<?php if ( $showContent ) { ?>
							<?php mpcustomblocks_portfolio_the_excerpt_maxcharlength($contentLength); 
						} ?>
						<?php if ( $showReadMore ) { ?>
							<span class="readmore"><?php echo $textReadMore; ?></span>
						<?php } ?>
					</div>
				</a>
			</div>

		<?php endwhile; ?>
		
		<?php
		
		wp_reset_postdata();
		ob_end_flush();
		wp_die();
	endif;
} 
/**/

// Post type
function mpcustomblocks_portfolio_get_post_type(){
	$args = array(
	   'public'   => true,
	);
    $post_types = get_post_types( $args, 'objects', 'and' );
	$allposttypes = [];
    foreach ( $post_types as $post_type ) {
		$allposttypes[] =  array(
		'value' => $post_type->name,
		'label' => __( $post_type->labels->name, )
	);
		//$post_type->name;
    }
	//echo '<pre>'; print_r($allposttypes); echo '</pre>';
	 return $allposttypes;
}

/* Ne fonctionne pas */
function mpcustomblocks_portfolio_get_taxonomies(){
	 $args = array(
	   'public'   => true,
	 );
    $taxonomies = get_taxonomies( $args, 'objects', 'and');
	$alltaxonomies = [];
    foreach ( $taxonomies as $taxonomy ) {
		$alltaxonomies[] =  array(
		'value' => $taxonomy->name,
		'label' => __( $taxonomy->label ),
		);
    }
	//echo '<pre>'; print_r($alltaxonomies); echo '</pre>';
	 return $alltaxonomies;
}

// Récupérer les tailles d'images enregistrées 
function mpcustomblocks_portfolio_get_image_sizes() {

	global $_wp_additional_image_sizes;
	$intermediate_image_sizes = get_intermediate_image_sizes();

	$image_sizes = array();
	foreach ( $intermediate_image_sizes as $size ) {
		if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
			$image_sizes[ $size ] = array(
				'width'  => $_wp_additional_image_sizes[ $size ][ 'width' ],
				'height' => $_wp_additional_image_sizes[ $size ][ 'height' ]
			);
		} 
		else {
			$image_sizes[ $size ] = array(
				'width'  => intval( get_option( "{$size}_size_w" ) ),
				'height' => intval( get_option( "{$size}_size_h" ) )
			);
		}
	}
	$sizes_arr = [];

	foreach ( $image_sizes as $key => $value ) {
		$temp_arr = [];
		$temp_arr[ 'value' ] = $key;
		$temp_arr[ 'label' ] = ucwords( strtolower( preg_replace( '/[-_]/', ' ', $key ) ) ) . " - {$value['width']} x {$value['height']}";
		$sizes_arr[] = $temp_arr;
	}

	$sizes_arr[] = array(
		'value' => 'full',
		'label' => __( 'Full Size', 'mpcustomblocks' )
	);
	return $sizes_arr;
}

// Extrait personnalisé
function mpcustomblocks_portfolio_the_excerpt_maxcharlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '...';
	} else {
		echo $excerpt;
	}
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function gutenberg_portfolio_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}
	
	wp_register_script(
		'gutenberg-portfolio',
		plugins_url( 'block.js', __FILE__ ),
		// Dépendances
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'underscore' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);
	
	wp_localize_script( 'gutenberg-portfolio', 'mpcb',
        array( 
			'post_type' => mpcustomblocks_portfolio_get_post_type(),
			'image_sizes' => mpcustomblocks_portfolio_get_image_sizes(),
			'taxonomies_list' => mpcustomblocks_portfolio_get_taxonomies()
        )
	);

	wp_register_style('mp_custom_blocks_main_css', plugins_url( 'style.css', dirname(__FILE__) ), array( ));
	// Style appliqué à Gutenberg uniquement
	wp_register_style('mp_custom_blocks_main_editor_css',plugins_url( 'editor.css', dirname(__FILE__) ), array( 'wp-edit-blocks' ));
	
	// Custom Block Main JS - Ajax load more
	wp_register_script( 'mp-custom-blocks-main', plugins_url( 'mp_customblocks_main.js',  dirname(__FILE__) ), array('jquery', 'theme'), true);
	
	if ( ! is_admin() ) {
        wp_enqueue_script( 'mp-custom-blocks-main' );
    }
	wp_localize_script( 
		'mp-custom-blocks-main', 'mp_loadmore', 
		array( 
			// ajax_url & loadmore déjà présent dans le bloc Mp Custom blocks Latest posts mais laissé si Latest Post est enlevé
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'loadmore' => __( 'Load more', 'mpcustomblocks' ),	
			'loading' => __( 'Loading...', 'mpcustomblocks' )
		) 
	);
	
	
	register_block_type( 'gutenberg-portfolio/my-portfolio', array(
		'editor_style' => 'mp_custom_blocks_main_editor_css',
		'style' => 'mp_custom_blocks_main_css',
		'editor_script' => 'gutenberg-portfolio',
		'attributes' => array(
			'alignment' => array(
                'type' => 'string',
                'default' => 'left',
            ),
			'postType' => array(
                'type' => 'string',
                'default' => 'post',
            ),
			'showTaxonomiesList' => array(
                'type' => 'boolean',
				'default' => false,
            ), 
			'taxonomySelected' => array(
                'type' => 'string',
				'default' => 'Tous',
            ), 
			'order' => array(
                'type' => 'string',
                'default' => 'desc',
            ),
            'orderBy' => array(
                'type' => 'string',
                'default' => 'date',
            ),
			'postsToShow' => array(
                'type' => 'number',
                'default' => 5,
            ),
            'showDate' => array(
                'type' => 'boolean',
                'default' => true,
            ),
			'showTitle' => array(
                'type' => 'boolean',
                'default' => true,
            ),
			'titleTag' => array(
                'type' => 'string',
                'default' => 'h3',
            ),
            'imageSize' => array(
                'type' => 'string',
                'default' => 'large',
            ),
			'showFeaturedImg' => array(
                'type' => 'boolean',
                'default' => true,
            ),	
			'showContent' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'contentLength' => array(
                'type' => 'number',
                'default' => 200,
            ),
			'showReadMore' => array(
                'type' => 'boolean',
                'default' => true,
            ),
			'textReadMore' => array(
                'type' => 'string',
                'default' => 'Read more',
            ),
			'showLoadMore' => array(
                'type' => 'boolean',
                'default' => false,
            ),
			'textLoadMore' => array(
                'type' => 'string',
                'default' => 'Load more',
            ),
			'textLoading' => array(
                'type' => 'string',
                'default' => 'Loading...',
            ),
			'colorAnimation' => array(
                'type' => 'string',
                'default' => '#000000',
            )
			/*
			'cropImages' => array(
				'type' => 'boolean',
				'default' => true,
			),         
            'showCategories' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'postLayout' => array(
                'type' => 'string',
                'default' => 'list',
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 3,
            ),
            'align' => array(
                'type' => 'string',
            ),
            'className' => array(
                'type' => 'string',
            ),    */     
        ),
		'render_callback' => 'gutenberg_portfolio_render_callback'
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'gutenberg-portfolio', 'mpcustomblocks' );
  }

}
add_action( 'init', 'gutenberg_portfolio_register_block' );