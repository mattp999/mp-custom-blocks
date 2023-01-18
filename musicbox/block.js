var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	InspectorControls = wp.blockEditor.InspectorControls,
	MediaUpload = wp.blockEditor.MediaUpload,
	RichText = wp.blockEditor.RichText;

	registerBlockType( 'gutenberg-musicbox/my-musicbox', {
		title: __( 'Music box', 'mpcustomblocks' ),
		icon: 'media-audio',
		category: 'mpcustomblocks',
		attributes: {
			title: {
				type: 'string',
    			source: 'html',
				selector: 'h2',
			},
			mediaID: {
				type: 'number',
			},
			mediaURL: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			streamingservices: {
				type: 'string',
    			source: 'html',
				selector: '.streamingservices',
			},
			releaseyear: {
				type: 'string',
    			source: 'html',
				selector: '.releaseyear',
			},
		},
		edit: function( props ) {

			var onSelectImage = function ( media ) {
				//console.log(media);
				var url;
				if (media.sizes.thumbnail.url) {
					url = media.sizes.thumbnail.url;
				} else {
					url = media.url;
				}
				props.setAttributes( { mediaURL: url } );
				props.setAttributes( { mediaID: media.id } );
			}
			


			return [
				/** INSPECTOR **
				el( InspectorControls,
				  {}, [
					el( "hr", {
					  style: {marginTop:20}
					}),
					el( SelectControl, {
						label: 'Image size',
						options: vvv.image_sizes,
						onChange: ( value ) => {
							props.setAttributes( { imageSize: value } );
						},
						value: props.attributes.imageSize,
					}),
				 ]
				),
				/**/
				el( 'div', { className: props.className },
					el( 'div', { className: 'album-cover' },
						el( MediaUpload, {
							onSelect: onSelectImage,
							allowedTypes: 'image',
							value: props.attributes.mediaID,
							render: function( obj ) {
								return el( wp.components.Button, {
										className: props.attributes.mediaID ? 'image-button' : 'button button-large',
										onClick: obj.open
									},
									! props.attributes.mediaID ? __( 'Upload Cover art', 'mpcustomblocks' ) : el( 'img', { src: props.attributes.mediaURL } )
								);
							}
						} )
					),
					el( RichText, {
						tagName: 'h2',
						inline: true,
						placeholder: __( 'Write Album title…', 'mpcustomblocks' ),
						value: props.attributes.title,
						onChange: function( value ) {
							props.setAttributes( { title: value } );
						},
					} ),
					el( 'h3', {}, __( 'Release year', 'mpcustomblocks' ) ),
					el( RichText, {
						tagName: 'div',
						inline: false,
						placeholder: __( 'Release year', 'mpcustomblocks' ),
						value: props.attributes.releaseyear,
						onChange: function( value ) {
							props.setAttributes( { releaseyear: value } );
						},
					} ),
					el( 'div', { className: 'album-links' },
						el( 'h3', {}, __( 'Listen / buy links', 'mpcustomblocks' ) ),
						el( RichText, {
							tagName: 'ul',
							multiline: 'li',
							placeholder: __( 'Write the list of streaming services…', 'mpcustomblocks' ),
							value: props.attributes.streamingservices,
							onChange: function( value ) {
								props.setAttributes( { streamingservices: value } );
							},
							className: 'streamingservices',
						} ),
					)
				)
			]
		},
		save: function( props ) {

			return (
				el( 'div', { className: props.className },
					props.attributes.mediaURL &&
						el( 'div', { className: 'album-cover' },
							el( 'img', { src: props.attributes.mediaURL } ),
						),
					el( RichText.Content, {
						tagName: 'h2', value: props.attributes.title
					} ),
					el( RichText.Content, {
						tagName: 'div', className: 'releaseyear', value: props.attributes.releaseyear
					} ),
					el( 'div', { className: 'album-links' },
						el( 'h3', {}, __( 'Listen / buy', 'musicbox' ) ),
						el( RichText.Content, {
							tagName: 'ul', className: 'streamingservices', value: props.attributes.streamingservices
						} ),
					)
				)
			);
		},
	} );