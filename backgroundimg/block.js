var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	InspectorControls = wp.blockEditor.InspectorControls,
	MediaUpload = wp.blockEditor.MediaUpload,
	// RichText = wp.blockEditor.RichText,
	SelectControl = wp.components.SelectControl,
	ToggleControl = wp.components.ToggleControl,
	RangeControl = wp.components.RangeControl,
	TextControl = wp.components.TextControl,
	PanelColorSettings = wp.blockEditor.PanelColorSettings,
	InnerBlocks = wp.blockEditor.InnerBlocks;

	registerBlockType( 'gutenberg-bgimage/my-bgimage', {
		title: __( 'Background image', 'mpcustomblocks' ),
		icon: 'format-image',
		category: 'mpcustomblocks',
		supports: {
			align: [ 'wide', 'full' ],
			anchor: true,
		},
		attributes: {
			minHeight: {
				type: 'string',
				default: '430px',
			},
			contentMaxWidth: {
				type: 'number',
				default: 1200,
			},
			mediaID: {
				type: 'number',
			},
			mediaURL: {
				type: 'string',
			},
			parallax: {
				type: 'boolean',
				default: false,
			},
			overlayColor: {
				type: 'string',
				default: 'transparent',
			},
			overlayOpacity: {
				type: 'number',
				default: 0,
			},
		},
		edit: function( props ) {
			
			// if(props.isSelected){
			  // console.debug(props.attributes);
			// };
			
			var onSelectImage = function ( media ) {
				//console.log(media);
				/*if ( ! media || ! media.id || ! media.url ) {
					props.setAttributes( { mediaID: undefined, mediaURL: undefined } );
					return;
				}*/
				props.setAttributes( { mediaID: media.id, mediaURL: media.url } );
			}
			var bgOpacity =  props.attributes.overlayOpacity === 100 ? '1' : '0.'+props.attributes.overlayOpacity;
			
			return [
				/** INSPECTOR **/
				el( InspectorControls,
				  {}, [
					el( "hr", {
					  style: {marginTop:20}
					}),
					el( TextControl,{
						label: 'Minimum height (px or vh)',
						onChange: ( value ) => {
							props.setAttributes( { minHeight: value } );
						},
						value: props.attributes.minHeight,	
					}),	
					el( "hr", {
					  style: {marginTop:20}
					}),
					el( RangeControl, {
						label: __( 'Maximum Content Width (px)', 'mpcustomblocks' ),
						min: 10,
						max: 1920,
						onChange: ( value ) => {
							props.setAttributes( { contentMaxWidth: parseInt( value, 10 ) } );
						},
						value: props.attributes.contentMaxWidth
					}),
					el( "hr", {
					  style: {marginTop:20}
					}),
					el( MediaUpload, {
						onSelect: onSelectImage,
						allowedTypes: 'image',
						value: props.attributes.mediaID,
						render: function( obj ) {
							return el( wp.components.IconButton, {
									icon: 'admin-media',
									onClick: obj.open
								},
								//! props.attributes.mediaID ? __( 'Upload background image', 'mpcustomblocks' ) : el( 'img', { src: props.attributes.mediaURL } )
								! props.attributes.mediaID ? __( 'Upload background image', 'mpcustomblocks' ) : __( 'Edit image', 'mpcustomblocks' ),
							);
						}
					}),
					el( "hr", {
					  style: {marginTop:20}
					}),
					el( ToggleControl, {
						label: 'Parallax',
						value: props.attributes.parallax,
						onChange: ( value ) => {
							props.setAttributes( { parallax: value } );
						},
						checked: props.attributes.parallax,
					} ),
					el( PanelColorSettings,{
						title: 'Overlay Color',
						colorSettings: [
							{
								value: props.attributes.overlayColor,
								label: 'Color',
								onChange: ( value ) => {
									props.setAttributes( { overlayColor: value } );
								},
							}
						]
					}),
					el( RangeControl, {
						label: __( 'Opacity', 'mpcustomblocks' ),
						min: 0,
						max: 100,
						onChange: ( value ) => {
							props.setAttributes( { overlayOpacity: parseInt( value, 10 ) } );
						},
						value: props.attributes.overlayOpacity
					}),

				 ]
				),
				/**/

				el( 'div', {
					className: props.className+' has-background-image-'+props.attributes.mediaID+' parallax-'+props.attributes.parallax,
					style: {backgroundImage: 'url('+props.attributes.mediaURL+')', minHeight: props.attributes.minHeight}	
					},
					el( 'div', {
						className: 'mpoverlay',
						style: {backgroundColor: props.attributes.overlayColor, opacity: bgOpacity}	
						},
					),
					el( 'div', {
							className: 'mpbgcontent',
							style: { maxWidth: props.attributes.contentMaxWidth+'px', width:'100%'}
						},
						el( InnerBlocks),
					),
				),
				
			]
		},
		save: function( props ) {
			
			var bgOpacity =  props.attributes.overlayOpacity === 100 ? '1' : '0.'+props.attributes.overlayOpacity;

			return el( 'div', { 
				className: props.className+' has-background-image-'+props.attributes.mediaID+' parallax-'+props.attributes.parallax,
				style: { minHeight: props.attributes.minHeight}
				},
				el( 'div', {
					className: 'mpoverlay',
					style: {backgroundColor: props.attributes.overlayColor, opacity: bgOpacity}
					},
				),
				el( 'div', {
						className: 'mpbgcontent',
						style: { maxWidth: props.attributes.contentMaxWidth+'px', width:'100%'}
					},
					el( InnerBlocks.Content ),
				),
			);
		}
	} );