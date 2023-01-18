// var name = require('./block.json');
var el = wp.element.createElement,
	/** Pour les traductions il suffit de faire __('Texte élément','textdomain')**/
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.serverSideRender,
	AlignmentToolbar = wp.blockEditor.AlignmentToolbar,
	BlockControls = wp.blockEditor.BlockControls;
	InspectorControls = wp.blockEditor.InspectorControls,
	SelectControl = wp.components.SelectControl,
	ToggleControl = wp.components.ToggleControl,
	TextControl = wp.components.TextControl,
	RangeControl = wp.components.RangeControl,
	Disabled = wp.components.Disabled;
	PanelColorSettings = wp.blockEditor.PanelColorSettings;

/** To declare only once for all MP Custom Blocks **/
const { __ } = wp.i18n; 
	
/** Set the icon for the block *
var mb_icon = el ("img", {
  src: "/wp-content/plugins/mb.gutemberg-block/images/YTPL.svg",
  width: "50px",
  height: "50px"
}); /**/
registerBlockType( 'gutenberg-portfolio/my-portfolio', {
	title: 'Portfolio',
	icon: 'grid-view',
	category: 'mpcustomblocks',
	// supports: { 
        // align: true,
    // },
	attributes: {
		alignment: {
			type: 'string',
			default: 'left',
		},
		postType: {
			type: 'string',
			default: 'post',
		},
		templateSelected: {
			type: 'string',
			default: 'default-template.php',
		},
		showTaxonomiesList: {
			type: 'boolean',
			default: false,
		},
		taxonomySelected:{
			type: 'string',
			default: 'Tous',
		},	
		order:{
            type: 'string',
            default: 'desc',
		},
        orderBy:{
            type: 'string',
            default: 'date',
           },
		postsToShow:{
			type: 'number',
			default: 5,
		},
		showDate: {
			type: 'boolean',
			default: true,
		},
		showTitle: {
			type: 'boolean',
			default: true,
		},
		titleTag: {
            type: 'string',
            default: 'h3',
        },
		showCategoriesOnPost: {
			type: 'boolean',
			default: false,
		},
		showFeaturedImg: {
			type: 'boolean',
			default: true,
		},
		imageSize: {
			type: 'string',
			default: 'large',
		},
		showContent: {
			type: 'boolean',
			default: false,
		},
		contentLength: {
			type: 'number',
			default: 200,
		},
		showReadMore: {
			type: 'boolean',
			default: true,
		},
		textReadMore: {
			type: 'string',
			default: 'Read more',
		},
		showLoadMore: {
			type: 'boolean',
			default: false,
		},
		textLoadMore: {
			type: 'string',
			default: 'Load more',
		},
		textLoading: {
			type: 'string',
			default: 'Loading...',
		},
		colorAnimation: {
			type: 'string',
			default: '#000000',
		}
	}, 		
	edit: (props) => {
    
		if(props.isSelected){
		  console.debug(props.attributes);
		};
		
		/** Fonction pour afficher le sous menu de 'Show category or taxonomy list' */
		var onToggleTaxo = function ( showTaxonomiesList ) {
			// console.log(showTaxonomiesList);
			props.setAttributes( { showTaxonomiesList: showTaxonomiesList } );
			var elementToHide = document.querySelector('.taxonomylist_options_to_show_or_hide');
			elementToHide.classList.toggle('mp_hide');
		}
		var onToggleShowTitle = function ( showTitle ) {
			props.setAttributes( { showTitle: showTitle } );
			var elementToHide = document.querySelector('.title_options_to_show_or_hide') ;
			elementToHide.classList.toggle('mp_hide');
		}
		var onToggleShowImg = function ( showFeaturedImg ) {
			props.setAttributes( { showFeaturedImg: showFeaturedImg } );
			var elementToHide = document.querySelector('.image_options_to_show_or_hide') ;
			elementToHide.classList.toggle('mp_hide');
		}
		var onToggleShowExcerpt = function ( showContent ) {
			props.setAttributes( { showContent: showContent } );
			var elementToHide = document.querySelector('.excerpt_options_to_show_or_hide') ;
			elementToHide.classList.toggle('mp_hide');
		}
		var onToggleShowReadMore = function ( showReadMore ) {
			props.setAttributes( { showReadMore: showReadMore } );
			var elementToHide = document.querySelector('.readmore_options_to_show_or_hide') ;
			elementToHide.classList.toggle('mp_hide');
		}
		var onToggleShowLoadMore = function ( showLoadMore ) {
			props.setAttributes( { showLoadMore: showLoadMore } );
			var elementToHide = document.querySelector('.loadmore_options_to_show_or_hide') ;
			elementToHide.classList.toggle('mp_hide');
		}
		
		return [
		  /**
		   * Server side render
		   */
		  el("div", {
				className: "mp_portfolio-editor-container",
				//style: {textAlign: "center"}
			  },
			   el(Disabled, {},
				  el( ServerSideRender, {
					block: 'gutenberg-portfolio/my-portfolio',
					attributes: props.attributes,
				  } )
			 ),  
		  ),
		  /**
		   * Toolbar
		   */
		!! focus && el( BlockControls, { key: 'controls' },
			el( AlignmentToolbar, {
				onChange: ( value ) => {
					props.setAttributes( { alignment: value } );
				},
				value: props.attributes.alignment
			}),
		), 
		/**
		* Inspector
		*/
		el( InspectorControls,
		  {}, [
			el( "hr", {
			  style: {marginTop:20}
			}),
			el( SelectControl, {
				label: __( 'Post Type', 'mpcustomblocks' ),
				options: mpcb.post_type,
				onChange: ( value ) => { props.setAttributes( { postType: value } ); },
				value: props.attributes.postType
			}),
			el( SelectControl, {
				label: __( 'Template', 'mpcustomblocks' ),
				options: mpcb.templates_list,
				onChange: ( value ) => { props.setAttributes( { templateSelected: value } ); },
				value: props.attributes.templateSelected
			}),
			el( ToggleControl, {
				label: __( 'Show category or taxonomy list', 'mpcustomblocks' ),
				className: 'mp_toggle_show_taxonomylist',
				value: props.attributes.showTaxonomiesList,
				onChange: onToggleTaxo,
				// onChange: ( value ) => { props.setAttributes( { showTaxonomiesList: value } ); },
				checked: props.attributes.showTaxonomiesList,
			} ),
			el( "div", {
				// Si showTaxonomiesList est coché au chargement de l'éditeur on affiche la div des options sinon on la cache
				className: (props.attributes.showTaxonomiesList === true ? 'taxonomylist_options_to_show_or_hide' : 'taxonomylist_options_to_show_or_hide mp_hide'),
				},
				 el( SelectControl, {
					label: __( 'Taxonomy to show', 'mpcustomblocks' ),
					options: mpcb.taxonomies_list,
					onChange: ( value ) => { props.setAttributes( { taxonomySelected: value } ); },
					value: props.attributes.taxonomySelected,
				}),	
			),	
			el( "hr", { style: {marginTop:20} }),			
			el( SelectControl, {
				label: __( 'Order', 'mpcustomblocks' ),
				options : [
					{ label: __( 'Ascendant', 'mpcustomblocks' ), value: 'asc' },
					{ label: __( 'Descendant', 'mpcustomblocks' ), value: 'desc' },
				],
				onChange: ( value ) => { props.setAttributes( { order: value } ); },
				value: props.attributes.order
			}),
			el( SelectControl, {
				label: __( 'Order by', 'mpcustomblocks' ),
				options : [
					{ label: __( 'None', 'mpcustomblocks' ), value: 'none' },
					{ label: __( 'ID', 'mpcustomblocks' ), value: 'ID' },
					{ label: __( 'Author', 'mpcustomblocks' ), value: 'author' },
					{ label: __( 'Title', 'mpcustomblocks' ), value: 'title' },
					{ label: __( 'Name', 'mpcustomblocks' ), value: 'name' },
					{ label: __( 'Date', 'mpcustomblocks' ), value: 'date' },
					{ label: __( 'Last modified date', 'mpcustomblocks' ), value: 'modified' },
					{ label: __( 'Parent', 'mpcustomblocks' ), value: 'parent' },
					{ label: __( 'Post type', 'mpcustomblocks' ), value: 'type' },
					{ label: __( 'Random', 'mpcustomblocks' ), value: 'rand' },
					{ label: __( 'Comments count', 'mpcustomblocks' ), value: 'comment_count' },
					{ label: __( 'Menu order', 'mpcustomblocks' ), value: 'menu_order' },
				],
				onChange: ( value ) => { props.setAttributes( { orderBy: value } ); },
				value: props.attributes.orderBy
			}),
			el( SelectControl, {
				label: __( 'Posts to show', 'mpcustomblocks' ),
				options : [
					{ label: '1', value: 1 },
					{ label: '2', value: 2 },
					{ label: '3', value: 3 },
					{ label: '4', value: 4 },
					{ label: '5', value: 5 },
					{ label: '6', value: 6 },
					{ label: '7', value: 7 },
					{ label: '8', value: 8 },
					{ label: '9', value: 9 },
					{ label: '10', value: 10 },
					{ label: __( 'All', 'mpcustomblocks' ), value: -1 },
				],
				onChange: ( value ) => { props.setAttributes( { postsToShow: parseInt( value, 10 ) } ); },
				value: props.attributes.postsToShow
			}),
			el( "hr", {
			  style: {marginTop:20}
			}),
			el( ToggleControl, {
				label: __( 'Show date', 'mpcustomblocks' ),
				value: props.attributes.showDate,
				onChange: ( value ) => { props.setAttributes( { showDate: value } ); },
				checked: props.attributes.showDate,
			} ),
			el( "hr", {
			  style: {marginTop:20}
			}),
			el( ToggleControl, {
				label: __( 'Show title', 'mpcustomblocks' ),
				value: props.attributes.showTitle,
				// onChange: ( value ) => { props.setAttributes( { showTitle: value } ); },
				onChange: onToggleShowTitle,
				checked: props.attributes.showTitle,
			} ),
			el( "div", {
				className: (props.attributes.showTitle === true ? 'title_options_to_show_or_hide' : 'title_options_to_show_or_hide mp_hide'),
				},
				el( SelectControl, {
					label: __( 'Title Tag', 'mpcustomblocks' ),
					options : [
						{ label: __( 'Paragraph', 'mpcustomblocks' ), value: 'p' },
						{ label: 'H2', value: 'h2' },
						{ label: 'H3', value: 'h3' },
						{ label: 'H4', value: 'h4' },
						{ label: 'H5', value: 'h5' },
						{ label: 'H6', value: 'h6' }
					],
					onChange: ( value ) => { props.setAttributes( { titleTag: value } ); },
					value: props.attributes.titleTag
				}),
			),
			el( "hr", { style: {marginTop:20} }),
			el( ToggleControl, {
				label: __( 'Show taxonomy terms on each post', 'mpcustomblocks' ),
				value: props.attributes.showCategoriesOnPost,
				onChange: ( value ) => { props.setAttributes( { showCategoriesOnPost: value } ); },
				checked: props.attributes.showCategoriesOnPost,
			} ),		
			el( "hr", {
			  style: {marginTop:20}
			}),
			el( ToggleControl, {
				label: __( 'Show Featured image', 'mpcustomblocks' ),
				value: props.attributes.showFeaturedImg,
				// onChange: ( value ) => { props.setAttributes( { showFeaturedImg: value } ); },
				onChange: onToggleShowImg,
				checked: props.attributes.showFeaturedImg
			}),
			el( "div", {
				className: (props.attributes.showFeaturedImg === true ? 'image_options_to_show_or_hide' : 'image_options_to_show_or_hide mp_hide'),
				},
				el( SelectControl, {
					label: __( 'Image size', 'mpcustomblocks' ),
					options: mpcb.image_sizes,
					onChange: ( value ) => { props.setAttributes( { imageSize: value } ); },
					value: props.attributes.imageSize
				}),
			),
			el( "hr", { style: {marginTop:20} }),
			el( ToggleControl, {
				label: __( 'Show Excerpt', 'mpcustomblocks' ),
				value: props.attributes.showContent,
				// onChange: ( value ) => { props.setAttributes( { showContent: value } ); },
				onChange: onToggleShowExcerpt,
				checked: props.attributes.showContent
			}),
			el( "div", {
				className: (props.attributes.showContent === true ? 'excerpt_options_to_show_or_hide' : 'excerpt_options_to_show_or_hide mp_hide'),
				},
				el( RangeControl, {
					label: __( 'Characters number', 'mpcustomblocks' ),
					min: 10,
					max: 500,
					onChange: ( value ) => { props.setAttributes( { contentLength: parseInt( value, 10 ) } ); },
					value: props.attributes.contentLength
				}),
			),
			el( "hr", { style: {marginTop:20} }),
			el( ToggleControl, {
				label: __( 'Show Read more', 'mpcustomblocks' ),
				value: props.attributes.showReadMore,
				// onChange: ( value ) => { props.setAttributes( { showReadMore: value } ); },
				onChange: onToggleShowReadMore,
				checked: props.attributes.showReadMore
			}),
			el( "div", {		
				className: (props.attributes.showReadMore === true ? 'readmore_options_to_show_or_hide' : 'readmore_options_to_show_or_hide mp_hide'),
				},
				el( TextControl,{
						label: __( 'Read more Text', 'mpcustomblocks' ),
						onChange: ( value ) => { props.setAttributes( { textReadMore: value } ); },
						value: props.attributes.textReadMore,
					}),
			),
			el( "hr", { style: {} }),
			el( ToggleControl, {
				label: __( 'Show "Load more"', 'mpcustomblocks' ),
				value: props.attributes.showLoadMore,
				// onChange: ( value ) => { props.setAttributes( { showLoadMore: value } ); },
				onChange: onToggleShowLoadMore,
				checked: props.attributes.showLoadMore
			}),
			el( "div", {
				className: (props.attributes.showLoadMore === true ? 'loadmore_options_to_show_or_hide' : 'loadmore_options_to_show_or_hide mp_hide'),
				},
				el( TextControl,
					{
						label: __( '"Load more" Button Text', 'mpcustomblocks' ),
						onChange: ( value ) => { props.setAttributes( { textLoadMore: value } ); },
						value: props.attributes.textLoadMore,
					}
				),
				el( TextControl,
					{
						label: __( '"Load more" loading Button Text', 'mpcustomblocks' ),
						onChange: ( value ) => { props.setAttributes( { textLoading: value } ); },
						value: props.attributes.textLoading,
					}
				),
			),
			el( PanelColorSettings,
				{
					title: __( 'Loading image color (on category menu click)', 'mpcustomblocks' ),
					colorSettings: [
						{
							value: props.attributes.colorAnimation,
							label: __( 'Color', 'mpcustomblocks' ),
							onChange: ( value ) => {
								props.setAttributes( { colorAnimation: value } );
							},
						}
					]
				}
			),
			
		  ]
	  )
	]
  },
  
  save: () => {
    /** this is resolved server side */
    return null
  }
} );