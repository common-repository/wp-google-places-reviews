/**
 * WP Google Places Reviews
 * Version 1.0.0
 */

// Menu tabs
jQuery(document).ready(function($){
	$('.hmp_menuItem').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('.hmp_menuItem').removeClass('show');
		$('.hmp_page').removeClass('show');

		$(this).addClass('show');
		$('#'+tab_id).addClass('show');
	});
});