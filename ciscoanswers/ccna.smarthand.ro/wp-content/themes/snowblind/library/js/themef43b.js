( function( $ ) {
	// Responsive videos
	var all_videos = $( '.entry-content' ).find( 'iframe[src*="player.vimeo.com"], iframe[src*="youtube.com"], iframe[src*="dailymotion.com"],iframe[src*="kickstarter.com"][src*="video.html"], object, embed' ),
		f_height;

	all_videos.each( function() {
		var video = $(this),
			aspect_ratio = video.attr( 'height' ) / video.attr( 'width' );

		video
			.removeAttr( 'height' )
			.removeAttr( 'width' );

		if ( ! video.parents( 'object' ).length )
			video.wrap( '<div class="responsive-video-wrapper" style="padding-top: ' + ( aspect_ratio * 100 ) + '%" />' );
	} );

	// Mobile menu
	$( '#page' ).on( 'click', '#mobile-menu a', function() {
		if ( $(this).hasClass( 'left-menu' ) )
			$( 'body' ).toggleClass( 'left-menu-open' );
		else
			$( '#drop-down-search' ).slideToggle( 'fast' );
	} );

	$( '#secondary, #left-nav' ).on( 'click', '.sub-menu-parent > a', function(e) {
		e.preventDefault();
		$(this).toggleClass( 'open' ).parent().find( '.sub-menu:first' ).toggle();
	} );

	var id = $( '#left-nav' );
	Harvey.attach( 'screen and (max-width:768px)', {
      	setup: function() {
      		id.addClass( 'offcanvas' );
	      	$( '#site-navigation' ).prependTo( id ).show();
		  	if ( $( 'body' ).hasClass( 'left-sidebar' ) )
		  		$( '#site-navigation' ).after( $( '#secondary' ) )
      	},
      	on: function() {
      		id.addClass( 'offcanvas' );
	      	$( '#site-navigation' ).prependTo( id );
			$( '.widget_search' ).hide();
		  	if ( $( 'body' ).hasClass( 'left-sidebar' ) )
		  		$( '#site-navigation' ).after( $( '#secondary' ) )

			$( '#left-nav' )
				.find( '.sub-menu-parent > a' ).removeClass( 'open' )
				.end()
				.find( '.sub-menu' ).hide();
      	},
      	off: function() {
      		id.removeClass( 'offcanvas' );
      		$( 'body' ).removeClass( 'left-menu-open' );
	      	$( '.widget_search, #site-navigation ul ul' ).show();
			$( '#site-navigation' ).appendTo( '#header' );
			$( '#primary' ).after( $( '#secondary' ) );
			$( '#drop-down-search' ).hide();
      	}
    } );

	$(window)
		.resize( function() {
			footer_height();
		} )
		.load( function() {
			footer_height();
		} );

	function footer_height() {
		f_height = $( '#footer-content' ).outerHeight() + 1;
		$( '#footer' ).css( { height: f_height + 'px' } );
		$( '#page' ).css( { marginBottom: -f_height + 'px', paddingBottom: f_height + 'px' } );
	}

	// Image anchor
	$( 'a:has(img)' ).addClass('image-anchor');

	$( 'a[href="#"]' ).click( function(e) {
		e.preventDefault();
	});
} )( jQuery );