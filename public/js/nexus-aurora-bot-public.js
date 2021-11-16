(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
	 $(function() {
		// We are replicating the astra menu functionality. This can be removed / updated when we update the entire menu
		var $menuToggle = $('.main-header-menu-toggle');
		var $mobileMenu = $('.main-header-bar-navigation');
		var $body = $('body');
		$menuToggle.click(function (e) {
			console.log('Menu Toggle Clicked');
			$(this).toggleClass('toggled');
			$mobileMenu.toggleClass('toggle-on').toggle();
			$body.toggleClass('ast-main-header-nav-open');
		});
	});
})( jQuery );
