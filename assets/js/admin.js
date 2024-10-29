;(function ($, window, document, undefined) {
	"use strict";

	var box_post_type = 'product',
		box_editor = $('.js-awv-table #box_editor').find('option:selected').val(),
		initialized_editor = false,
		editor;

	function awv_init_box_editor() {
		if ( awv_data ) {
			awv_init_editor();
		}
	}


	// Init code(html) editor
	function awv_init_editor() {
		if ( box_editor == 'html' && ! initialized_editor ) {
			editor = wp.codeEditor.initialize( $('#html_data') );
			initialized_editor = true;
		}
	}


	// Change Box editor type
	$('.js-awv-table #box_editor').on('change', function() {
		box_editor = $(this).find('option:selected').val();
		if ( box_editor == 'html' ) {
			$('.awv-html-editor').show();
			$('.line-elmns').hide();
			awv_init_editor();
		} else {
			$('.awv-html-editor').hide();
			$('.line-elmns').show();
		}
	});


	// Change display type
	$('.js-awv-table #view_automatically').on('change', function() {
		var view_automatically = $(this).find('option:selected').val();

		if ( view_automatically == 'yes' ) {
			$('#box_position_row').removeClass('hidden-row');
			$('#box_position_priority_row').removeClass('hidden-row');
		} else {
			$('#box_position_row').addClass('hidden-row');
			$('#box_position_priority_row').addClass('hidden-row');
		}
	});


	// Change Box position
	$('.js-awv-table #box_position').on('change', function() {
		var $select = $(this),
			priority_ui = $select.find('option:selected').attr('data-priority_ui'),
			$priority_row = $('#box_position_priority_row');
			
		if ( ! $('#position_priority').val() ) {
			$('#position_priority').val( $select.find('option:selected').attr('data-priority') );
		}

		if ( priority_ui == 'show' ) {
			$priority_row.removeClass('hidden-row');
		} else {
			$priority_row.addClass('hidden-row');
		}
	});


	// Window load action
	$(window).on('load', function() {
		if ( window.awv_data && window.awv_data.box_post_type ) {
			awv_init_box_editor();
		}
	});


	// Init lile editor fields list
	$('#awv-fields-container').sortable({
		handle: '.awv-cpacicon-move'
	});


	// Add field to the line or html editor
	$('.awv-field-buttons').on('click', '.awv-add-field', function(e){
		var field = $(this);

		if ( box_editor == 'line' ) {
			var $container = $('#awv-fields-container'),
				key = 0;

			$container.find('.awv-feild-item').each(function(){
				var item_no = parseInt( $(this).attr('data-key') );
				if ( item_no > key ) {
					key = item_no;
				}
			});

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: ({
					action: 'awv_get_field_row',
					nonce: awv_data.ajax_nonce,
					field_type: field.attr('data-type'),
					field: field.attr('data-name'),
					field_name: field.text(),
					count_items: key + 1,
					editor: 'line'
				}),
				success: function( field_row ) {
					$container.find('.awv-feild-item.active').removeClass('active');
					$container.prepend(field_row);
				}
			});
		}

		if ( box_editor == 'html' ) {
			var $popup_wrapper = $('.awv-popup-content');
			$('.awv-popup-overlay').addClass('active');
			$('.awv-popup').addClass('active');

			$popup_wrapper.text( awv_data.loading_text );
			$('.awv-popup-title').text(field.text());

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: ({
					action: 'awv_get_field_row',
					nonce: awv_data.ajax_nonce,
					field_type: field.attr('data-type'),
					field: field.attr('data-name'),
					field_name: field.text(),
					count_items: 999,
					editor: 'html'
				}),
				success: function( field_row ) {
					$popup_wrapper.html(field_row);
				}
			});
		}

		e.preventDefault();
	});



	// Remove line editor field
	$('#awv-fields-container').on('click', '.awv-remove-button', function(e){
		$(this).closest('.awv-feild-item').remove();
		e.preventDefault();
	});

	
	// Open line editor field options
	$('#awv-fields-container').on('click', '.awv-edit-button', function(e){
		$('#awv-fields-container').find('.awv-feild-item.active').removeClass('active');
		$(this).closest('.awv-feild-item').addClass('active');
		e.preventDefault();
	});


	// Close line editor field options
	$('#awv-fields-container').on('click', '.awv-close-button', function(e){
		$(this).closest('.awv-feild-item').removeClass('active');
		e.preventDefault();
	});


	// Close popup
	$('.awv-popup-close, .awv-button-cancel, .awv-popup-overlay').on('click', function(){
		$('.awv-popup-overlay').removeClass('active');
		$('.awv-popup').removeClass('active');
	});

	
	// Insert shortcode to the editor
	$('.awv-button-insert').on('click', function(){
		var cm = editor.codemirror,
			shortcode = '[awv_field';
		
		cm.focus();

		$('.awv-popup-overlay').removeClass('active');
		$('.awv-popup').removeClass('active');

		var doc = cm.getDoc();

		$(this).closest('.awv-popup').find('.acfv-field').each(function(){
			var $field = $(this);

			if ( $field.attr('data-type') == 'text' && $field.val() ) {
				shortcode += ' ' + $field.attr('data-field') + '="' + $field.val() + '"';
			}

			if ( $field.attr('data-type') == 'select' ) {
				shortcode += ' ' + $field.attr('data-field') + '="' + $field.find(':selected').val() + '"';
			}
		});


		doc.replaceRange(shortcode + ']', doc.getCursor());
	});

})(jQuery, window, document);