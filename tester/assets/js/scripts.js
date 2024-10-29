;(function ($, window, document, undefined) {
	"use strict";


	$('.awv_tester-button').on('click', function(){
		$(this).closest('.awv_tester-form-wrapper').toggleClass('active');
	})

})(jQuery, window, document);