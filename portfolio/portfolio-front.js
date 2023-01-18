jQuery.noConflict();  

/* Namespace to plug external function mp_portfolio.bindElAfterReload() | Create this in your js file : mp_portfolio.bindElAfterReload = function(){ //Stuff to do... } */
var mp_portfolio = {};

jQuery(function($){
	/* Lazy Load
	const observer = lozad('.lozad', {
		rootMargin: '200px 0px', // syntax similar to that of CSS Margin
		threshold: 0.01 // ratio of element convergence
	}); /**/
	
	var touchMoved;
	
	/*********************
	****  PORTFOLIO	  ****
	*********************/
	var categorieID = 0;
	var alignment;
	var posttype;
	var templateselected;
	var taxonomyselected;
	var poststoshow;
	var order;
	var orderby;
	var showdate;
	var showtitle;
	var titletag;
	var showCategoriesOnPost;
	var showfeaturedimg;
	var imagesize;
	var showcontent;
	var contentlength;
	var showreadmore;
	var textreadmore;
	var textLoadMore;
	var showLoadMore;
	var cliccategorieorloadmore;
	
	/* Clic sur une catégorie */	
	jQuery( ".mp_portfolio ul li" ).on('click touchend', function () {
		if(touchMoved != true){
			if(jQuery(this).hasClass("active")){
				return false;
			}
			else
			{
				jQuery(".mp_portfolio ul li").removeClass("active");
				jQuery(this).addClass("active");
				categorieID = jQuery(this).attr("data-mpcbid");
				alignment = jQuery(this).parent().next().next().attr('data-alignment');
				posttype = jQuery(this).parent().next().next().attr('data-posttype');
				templateselected = jQuery(this).parent().next().next().attr('data-templateselected');
				taxonomyselected = jQuery(this).parent().next().next().attr('data-taxonomyselected');
				poststoshow = jQuery(this).parent().next().next().attr('data-poststoshow');
				order = jQuery(this).parent().next().next().attr('data-order');
				orderby = jQuery(this).parent().next().next().attr('data-orderby');
				showdate = jQuery(this).parent().next().next().attr('data-showdate');
				showtitle = jQuery(this).parent().next().next().attr('data-showtitle');
				titletag = jQuery(this).parent().next().next().attr('data-titletag');
				showcategoriesonpost = jQuery(this).parent().next().next().attr('data-showcategoriesonpost');
				showfeaturedimg = jQuery(this).parent().next().next().attr('data-showfeaturedimg');
				imagesize = jQuery(this).parent().next().next().attr('data-imagesize');
				showcontent = jQuery(this).parent().next().next().attr('data-showcontent');
				contentlength = jQuery(this).parent().next().next().attr('data-contentlength');
				showreadmore = jQuery(this).parent().next().next().attr('data-showreadmore');
				textreadmore = jQuery(this).parent().next().next().attr('data-textreadmore');
				showloadmore = jQuery(this).parent().next().next().attr('data-showloadmore');
				textloadmore = jQuery(this).parent().next().next().attr('data-textloadmore');
				
				reload_portfolio_ajax(categorieID, alignment, posttype, templateselected, taxonomyselected, poststoshow, order, orderby, showdate, showtitle, titletag, showcategoriesonpost, showfeaturedimg, imagesize, showcontent, contentlength, showreadmore, textreadmore);
			}
		}
	}).on('touchmove', function(e){
			touchmoved = true;
		}).on('touchstart', function(){
			touchmoved = false;
		});
		
	/* Fonction appelée après clic sur une catégorie  */
	function reload_portfolio_ajax(categorieID, alignment, posttype, templateselected, taxonomyselected, poststoshow, order, orderby, showdate, showtitle, titletag, showcategoriesonpost, showfeaturedimg, imagesize, showcontent, contentlength, showreadmore, textreadmore) {
		jQuery('.mp_portfolio.mp_portfolio_'+posttype+' .container-animation svg').show();
		jQuery(".mp_portfolio.mp_portfolio_"+posttype+" .newswrapper").html('');
		cliccategorieorloadmore ='cliccategorie';

		var ajaxurl = mp_loadmore.ajax_url;
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "reload_portfolio", catID: categorieID, alignmenT:alignment, postType: posttype, templateSelected: templateselected, taxonomySelected: taxonomyselected, 
			postsToShow: poststoshow, ordeR: order, orderBy: orderby, showDate: showdate, showTitle: showtitle, titleTag: titletag, showCategoriesOnPost:showcategoriesonpost, 
			showFeaturedImg: showfeaturedimg, imageSize : imagesize, showContent: showcontent, contentLength: contentlength, showReadMore: showreadmore, textReadMore: textreadmore, showLoadMore :showloadmore, 
			clicCategorieorLoadMore:cliccategorieorloadmore },
			success: function(response) {
				jQuery('.mp_portfolio.mp_portfolio_'+posttype+' .container-animation svg').hide();
				// jQuery(".mp_portfolio.mp_portfolio_"+posttype+" .newswrapper").html(response);
				
				jQuery.when( jQuery(".mp_portfolio.mp_portfolio_"+posttype+" .newswrapper").html(response) ).done(function( ) {
					// Rebind elements if needed through the external function mp_portfolio.bindElAfterReload()
					if (mp_portfolio.bindElAfterReload){
						mp_portfolio.bindElAfterReload();
					}
				});
				

				/* Si le bouton "Charger +" a été supprimé sur un précédent défilmement, on le réaffiche */
				if(!jQuery('.mp_portfolio.mp_portfolio_'+posttype+' button.mp_custom_blocks_loadmore_portfolio').is(":hidden") && showloadmore ==1 ){
					jQuery('.mp_portfolio.mp_portfolio_'+posttype+' button.mp_custom_blocks_loadmore_portfolio').css("visibility", "visible");
				}
				
				// On supprime le bouton "Charger +" si on n'a qu'une seule page au clic de la catégorie
				if ( showloadmore ==1 && window["current_page_ajax"+posttype] == window["max_page_ajax"+posttype] ){
						jQuery('.mp_portfolio.mp_portfolio_'+posttype+' button.mp_custom_blocks_loadmore_portfolio').css("visibility", "hidden");
				}
				return false;
			}
		});
	}
	/* Clic bouton Charger + */
	$('.mp_custom_blocks_loadmore_portfolio').on('click touchend', function(e){
		if(touchMoved != true){
			if(jQuery(this).prev().prev().prev('ul').children('li.cat.active').length){
				categorieID = jQuery(this).prev().prev().prev().children('li.cat.active').attr('data-mpcbid');
				// console.log("categorieID 2 ="+categorieID);
			}
			else{
				categorieID = 0;
				// console.log("categorieID100 ="+categorieID);
			}
			posttype = jQuery(this).prev().attr('data-posttype');
			templateselected = jQuery(this).prev().attr('data-templateselected');
			taxonomyselected = jQuery(this).prev().attr('data-taxonomyselected');
			poststoshow = jQuery(this).prev().attr('data-poststoshow');
			order = jQuery(this).prev().attr('data-order');
			orderby = jQuery(this).prev().attr('data-orderby');
			showdate = jQuery(this).prev().attr('data-showdate');
			showtitle = jQuery(this).prev().attr('data-showtitle');
			titletag = jQuery(this).prev().attr('data-titletag');
			showcategoriesonpost = jQuery(this).prev().attr('data-showcategoriesonpost');
			showfeaturedimg = jQuery(this).prev().attr('data-showfeaturedimg');
			imagesize = jQuery(this).prev().attr('data-imagesize');
			showcontent = jQuery(this).prev().attr('data-showcontent');
			contentlength = jQuery(this).prev().attr('data-contentlength');
			showreadmore = jQuery(this).prev().attr('data-showreadmore');
			textreadmore = jQuery(this).prev().attr('data-textreadmore');
			textLoadMore = jQuery(this).prev().attr('data-textLoadMore');
			showloadmore = jQuery(this).prev().attr('data-showloadmore');			
			textLoading = jQuery(this).prev().attr('data-textloading');	
			
			cliccategorieorloadmore ='clicloadmore';
			
			var button = $(this);
			
				data = {
					'action': 'reload_portfolio',
					'page' : window["current_page_ajax"+posttype],
					catID: categorieID, postType: posttype, postsToShow: poststoshow, templateSelected: templateselected, taxonomySelected: taxonomyselected, ordeR: order, 
					orderBy: orderby, showDate: showdate, showTitle: showtitle, titleTag: titletag, showCategoriesOnPost: showcategoriesonpost, showFeaturedImg: showfeaturedimg, 
					imageSize : imagesize, showContent: showcontent, contentLength: contentlength, showReadMore: showreadmore, textReadMore: textreadmore, showLoadMore :showloadmore, 
					clicCategorieorLoadMore:cliccategorieorloadmore,
				};
			$.ajax({
				url : mp_loadmore.ajax_url, // AJAX handler
				data : data, 
				type : 'POST',
				beforeSend : function ( xhr ) {
					button.find('span').addClass('active').text(textLoading); // change the button text
					button.find('svg').css({'display':'inline-block'});
				},		
				success : function( data ){
					if( data ) { 
						//console.log('success');
						button.find('span').removeClass('active').text(textLoadMore);
						button.find('svg').css("display", "none");
						
						// insert new posts						
						jQuery.when( button.prev().children('div.mp_post').last().after(data) ).done(function( ) {
							// Rebind elements if needed through the external function mp_portfolio.bindElAfterReload()
							if (mp_portfolio.bindElAfterReload){
								mp_portfolio.bindElAfterReload();
							}
						});
						
						window["current_page_ajax"+posttype]++;
						
						if ( window["current_page_ajax"+posttype] == window["max_page_ajax"+posttype] )
							button.css("visibility", "hidden"); // if last page, remove the button
					} else {
						//button.remove(); // if no data, remove the button as well
					}
				}
			});
		}
	}).on('touchmove', function(e){
			touchmoved = true;
		}).on('touchstart', function(){
			touchmoved = false;
		});
 /**/
});