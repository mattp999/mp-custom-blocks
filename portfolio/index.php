<?php
/**
 * Plugin Name: Gutenberg Portfolio
 * Description: This is a plugin to show a specific Post type in Portfolio in Gutenberg Blocks
 * Version: 1.1
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
	
	$templateSelected = $attributes['templateSelected'];	
	$taxonomySelected = $attributes['taxonomySelected'];	
	$showTaxonomiesList = $attributes['showTaxonomiesList'];	
	$sectionAlignment = $attributes['alignment'];
	$showDate = isset( $attributes['showDate'] ) && $attributes['showDate'];
	$showTitle = isset( $attributes['showTitle'] ) && $attributes['showTitle'];
	$titleTag = $attributes['titleTag'];
	$showCategoriesOnPost = isset($attributes['showCategoriesOnPost']) && $attributes['showCategoriesOnPost'];
	$showFeaturedImg = isset( $attributes['showFeaturedImg'] ) && $attributes['showFeaturedImg'];
	$imageSize = ( ( isset($attributes['imageSize']) && $attributes['imageSize'] ) ? $attributes['imageSize'] : 'post-thumbnail');
	$showContent = isset($attributes['showContent']) && $attributes['showContent'];
	$contentLength = isset($attributes['contentLength']) && $attributes['contentLength'];
	$showReadMore = isset($attributes['showReadMore']) && $attributes['showReadMore'];
	$textReadMore = $attributes['textReadMore'];
	$showLoadMore = isset($attributes['showLoadMore']) && $attributes['showLoadMore'];	
	
    $query = new WP_Query( $query_args );

    ob_start();
    ?>    

    <div class="mp_portfolio <?php echo 'mp_portfolio_'.$attributes['postType'].' mp_'.$templateSelected ?>">
        
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
		if ($showTaxonomiesList){ 
			// Liste catégories Post
			if ($attributes['postType'] === 'post' && $taxonomySelected === 'category'){
				$terms = get_categories($taxonomySelected);				
			}
			else{
			// Liste taxonomies
				$terms = get_terms($taxonomySelected, array(
					'orderby'    => 'name',
					'order'    => 'DESC'
				));
			}
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				echo '<ul>';
				echo '<li data-mpcbid="0" class="cat active">'.__( 'All', 'mpcustomblocks' ).'</li>';
				foreach ( $terms as $term ) {
					echo '<li data-mpcbid="'.$term->term_id.'" class="cat">'.__( $term->name ).'</li>';
				}
				echo '</ul>';
			} 
		} ?>

			<div class="container-animation">			
				<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="44px" height="44px" viewBox="0 0 128 128" xml:space="preserve">
					<g><path fill="<?php echo $attributes['colorAnimation'] ?>" d="M26.9 65.08c3.87 21.1 21.26 37 42.13 37 23.72 0 41.6-20.58 42.95-45.88 1-18.84-9.45-37.5-32.57-47.88A52.2 52.2 0 0 0 47.08 5c22.23-6.02 41.53.02 54.6 10.66 8.2 6.46 16.12 15.33 19.32 24.4a67.13 67.13 0 0 1 3.77 19.85c0 34.4-26.87 62.3-61.26 62.3A62.27 62.27 0 0 1 2.05 70.1c.57-15.82 19.83-18.23 24.83-5.02z"/>
					<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform></g>
				</svg>		
			</div>
			
			<div class="newswrapper"
				data-alignment="<?php echo $attributes['alignment'];?>"
				data-posttype="<?php echo $attributes['postType'];?>"
				data-templateselected="<?php echo $templateSelected;?>"
				data-taxonomyselected="<?php echo $taxonomySelected;?>"
				data-poststoshow="<?php echo $attributes['postsToShow'];?>"
				data-order="<?php echo $attributes['order'];?>"
				data-orderby="<?php echo $attributes['orderBy'];?>"
				data-showdate="<?php echo $attributes['showDate'];?>"
				data-showtitle="<?php echo $attributes['showTitle'];?>"
				data-titletag="<?php echo $attributes['titleTag'];?>"				
				data-showcategoriesonpost="<?php echo $attributes['showCategoriesOnPost'];?>"
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
					$query->the_post(); 
					
					mp_portfolio_show_post($templateSelected, $sectionAlignment, $showFeaturedImg, $imageSize, $showTitle, $titleTag, $showCategoriesOnPost, $taxonomySelected, $showContent, $contentLength, $showDate, $showReadMore, $textReadMore);
				
				endwhile; ?>
				
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
			<?php } 
			
			wp_reset_postdata();
			ob_end_flush();
		endif; ?>
        
    </div>

    <?php
    $result = ob_get_clean();
    return $result;
}


/**********************************************************
*   Affichage Posts AJAX CLIC CATEGORIE ou LOAD MORE  	  *
***********************************************************/
// Réponse au clic catégorie portfolio ou bouton "Charger +"
add_action( 'wp_ajax_nopriv_reload_portfolio', 'reload_portfolio_response' );
add_action( 'wp_ajax_reload_portfolio', 'reload_portfolio_response' );
function reload_portfolio_response () {
    $cat_id = $_POST[ 'catID' ];
    $postType = $_POST[ 'postType' ];
    $templateSelected = $_POST[ 'templateSelected' ];
    $taxonomySelected = $_POST[ 'taxonomySelected' ];
    $sectionAlignment = $_POST[ 'alignmenT' ];
    $postsToShow = $_POST[ 'postsToShow' ];
    $order = $_POST[ 'ordeR' ];
    $orderBy = $_POST[ 'orderBy' ];
    $showDate = $_POST[ 'showDate' ];
    $showTitle = $_POST[ 'showTitle' ];
    $titleTag = $_POST[ 'titleTag' ];
	$showCategoriesOnPost = $_POST['showCategoriesOnPost'];
    $showFeaturedImg = $_POST[ 'showFeaturedImg' ];
    $imageSize = $_POST[ 'imageSize' ];
    $showContent = $_POST[ 'showContent' ];
    $contentLength = $_POST[ 'contentLength' ];
    $showReadMore = $_POST[ 'showReadMore' ];
    $textReadMore = $_POST[ 'textReadMore' ];
	$showLoadMore = $_POST['showLoadMore'];
	//  On vérifie si l'action est le clic sur le menu catégorie ou LoadMore || 'cliccategorie' 'clicloadmore'
	$clicCategorieorLoadMore = $_POST['clicCategorieorLoadMore'];
	
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
	if ($taxonomySelected != '' && $cat_id != 0){ 
		// if ($cat_id != 0 && $postType === 'post') {
		if ($postType === 'post' && $taxonomySelected === 'category') {
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
		// if ($cat_id != 0 && $postType !== 'post') {
		else{
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomySelected,
					'field' => 'term_id',
					'terms' => array( $cat_id )
				)
			);
		}
	}	
	
	$query = new WP_Query( $query_args );
	if ( $query->have_posts() ):
				
		ob_start();
		while( $query->have_posts() ):
			$query->the_post(); 
			
			mp_portfolio_show_post($templateSelected, $sectionAlignment, $showFeaturedImg, $imageSize, $showTitle, $titleTag, $showCategoriesOnPost, $taxonomySelected, $showContent, $contentLength, $showDate, $showReadMore, $textReadMore); ?>
				
		<?php endwhile; ?>
		
		<!-- Ajax Load more Params 2 -->
		<?php // Si on a cliqué sur une catégorie, on réactualise les infos de numérotation de page
		if ( $showLoadMore && $clicCategorieorLoadMore === 'cliccategorie') { ?>
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

// Affichage de chaque post. Fonction appelée au chargement de la page front / back office + clic taxonomy sur menu et clic bouton Load More
function mp_portfolio_show_post($templateSelected, $sectionalignment, $showfeaturedimg, $imagesize, $showtitle, $titletag, $showcategoriesonpost, $taxonomyselected, $showcontent, $contentlength, $showdate, $showreadmore, $textreadmore){	
		
	// Load selected template from plugin directory
	$pluginPath = plugin_dir_path(__FILE__) .'mp_portfolio-templates';
	$templateName = '/' .$templateSelected.'.php';
	if (file_exists($pluginPath . $templateName)){ include($pluginPath . $templateName); }
	// Or theme directory
	else{
		$themePath = get_stylesheet_directory() .'/mp_portfolio-templates';
		if (file_exists($themePath . $templateName)){ include($themePath . $templateName); }
	}
}

// Recupérer les templates existants
function mpcustomblocks_get_portfolio_templates(){
	
	// $pathArray = [];
	$templateNames = [];	
	// Load templates in plugin's dedicated directory & active theme's dedicated directory
	$pathArray[] = plugin_dir_path(__FILE__) .'mp_portfolio-templates';
	$pathArray[] = get_stylesheet_directory() .'/mp_portfolio-templates';
	$path = plugin_dir_path(__FILE__) .'mp_portfolio-templates';
	foreach ( $pathArray as $path ) {
		// Check if valid directory then add each file to templateNames array
		if (is_dir($path)) {
			$files = array_diff(scandir($path), array('..', '.'));				
			foreach ( $files as $file ) {
				$templateNames[] =  array(
					'value' => rtrim($file,'.php'),
					'label' => __(  ucwords(str_replace('-', ' ', rtrim($file,'.php'))), )
				);
			}
		}
	}
	// echo '<pre>test'; print_r($templateNames); echo '</pre>';
	 return $templateNames;
}
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

/* Récupérer les taxonomies du site */
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
		'mp_portfolio_block',
		plugins_url( 'block.js', __FILE__ ),
		// Dépendances
		array( 'wp-i18n', 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'underscore' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);	
	wp_localize_script( 'mp_portfolio_block', 'mpcb',
        array( 
			'post_type' => mpcustomblocks_portfolio_get_post_type(),
			'image_sizes' => mpcustomblocks_portfolio_get_image_sizes(),
			'taxonomies_list' => mpcustomblocks_portfolio_get_taxonomies(),
			'templates_list' => mpcustomblocks_get_portfolio_templates()
        )
	);
	wp_register_style('mp_portfolio_style', plugins_url( '/portfolio/portfolio-style.css', dirname(__FILE__) ), array( ));
	wp_register_style('mp_portfolio_editor_style',plugins_url( '/portfolio/portfolio-editor.css', dirname(__FILE__) ), array( 'wp-edit-blocks' ));
	// Front JS
	wp_register_script( 'mp_portfolio_front', plugins_url( '/portfolio/portfolio-front.js',  dirname(__FILE__) ), array('jquery'), true);
	// Editor JS
	// wp_register_script( 'mp_portfolio_editor_script', plugins_url( '/portfolio/portfolio-editor.js',  dirname(__FILE__) ), array('jquery'), true);
	
	// if ( ! is_admin() ) { wp_enqueue_script( 'mp_portfolio_front' ); }
	
	wp_localize_script( 
		'mp_portfolio_front', 'mp_loadmore', 
		array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'loadmore' => __( 'Load more', 'mpcustomblocks' ),
			'loading' => __( 'Loading...', 'mpcustomblocks' )
		) 
	);	
	
	// On enregistre le bloc avec les infos de block.json
	// register_block_type(__DIR__, [
		// 'render_callback' => 'gutenberg_portfolio_render_callback',
	// ] );
	
	register_block_type( 'gutenberg-portfolio/my-portfolio', array(
		'editor_style' => 'mp_portfolio_editor_style',
		'editor_script' => 'mp_portfolio_block',
		'script' => 'mp_portfolio_front',
		'style' => 'mp_portfolio_style',	
		'attributes' => array(
			'alignment' => array(
                'type' => 'string',
                'default' => 'left',
            ),
			'postType' => array(
                'type' => 'string',
                'default' => 'post',
            ),
			'templateSelected' => array(
                'type' => 'string',
                'default' => 'default-template.php',
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
            'showCategoriesOnPost' => array(
                'type' => 'boolean',
                'default' => false,
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
			// 'cropImages' => array(
				// 'type' => 'boolean',
				// 'default' => true,
			// ), 
            // 'postLayout' => array(
                // 'type' => 'string',
                // 'default' => 'list',
            // ),
            // 'columns' => array(
                // 'type' => 'number',
                // 'default' => 3,
            // ),
            // 'align' => array(
                // 'type' => 'string',
            // ),
            // 'className' => array(
                // 'type' => 'string',
            // ),      
        ),
		'render_callback' => 'gutenberg_portfolio_render_callback'
	) );
	/**/
}
add_action( 'init', 'gutenberg_portfolio_register_block' );