(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

})( jQuery );

function getCSVExport(){

	jQuery('a[name="csvexp_ord"]').on('click', function(e) {
		e.preventDefault();
		console.log('click');
		jQuery.ajax ({
			url: '/wp-admin/admin-ajax.php',
	  		type: 'GET',
	  		data: {

				action : 'call_export_csv',
				'filter_date_empty':jQuery('#filter-by-date').val() == "0",
				'filterstartdate':jQuery.datepicker.formatDate('dd/mm/yy',get_filter_start_day()),
				'filterenddate':jQuery.datepicker.formatDate('dd/mm/yy',get_filter_end_day()),
				'customeremail': get_customer(),
				'status':jQuery('a.current').text().split('Orders')[1].split(' (')[0],
				'customer_empty':jQuery('span.select2-selection__placeholder').text() == "Filter by registered customer",

			},
			success: function (resp) {
				
				if (resp.success) {
					
					// if you wanted to use the return value you set 
					// in your_function(), you would do so via
					// resp.data, but in this case I guess you don't
					// need it
					console.log(resp);
					generate_csv_callback(resp);
					// jQuery('a[name="csvexp_ord"]').href = resp.path;		
					
				}
				else {
					
					// this "error" case means the ajax call, itself, succeeded, but the function
					// called returned an error condition
					console.log('Error: ' + resp.data.return_value);

				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
					
				// this error case means that the ajax call, itself, failed, e.g., a syntax error
				// in your_function()
				console.log(xhr.responseText);
				console.log('Request failed: ' + thrownError.message);

			},
			fail:function (xhr, ajaxOptions, thrownError) {
				
				// this fail case means that the ajax call, itself, failed, e.g., a syntax error
				// in your_function()
				console.log('Request failed: ' + thrownError.message);

			},
		});
	});

}

function get_customer(){
	
	customer_val = '';
	if(jQuery('#select2-_customer_user-ij-container').attr('title') != null)
	{
		customer_val = jQuery('#select2-_customer_user-ij-container').attr('title').split('; ')[1].split(')')[0];
	}
	return customer_val;

}

function generate_csv_callback(response){
	
	var a = window.document.createElement('a');
	a.href = window.URL.createObjectURL(new Blob(response.data.data, {type: 'text/csv'}));
	a.download = 'Order stat report as at '+Date()+'.csv';

	// Append anchor to body.
	document.body.appendChild(a);
	a.click();

	// Remove anchor from body
	document.body.removeChild(a);

}


function set_filter_dates(){
	
	month = jQuery('#filter-by-date').val().charAt(4).concat(jQuery('#filter-by-date').val().charAt(5));
	year = jQuery('#filter-by-date').val().charAt(0).concat(jQuery('#filter-by-date').val().charAt(1)).concat(jQuery('#filter-by-date').val().charAt(2)).concat(jQuery('#filter-by-date').val().charAt(3));

}

function get_filter_start_day(){
	
	month = jQuery('#filter-by-date').val().charAt(4).concat(jQuery('#filter-by-date').val().charAt(5));
	year = jQuery('#filter-by-date').val().charAt(0).concat(jQuery('#filter-by-date').val().charAt(1)).concat(jQuery('#filter-by-date').val().charAt(2)).concat(jQuery('#filter-by-date').val().charAt(3));
	
	if(jQuery('#filter-by-date').val() != "0"){
		return new Date(year, month, 1);
	}

	return '';
}

function get_filter_end_day(){
	
	month = jQuery('#filter-by-date').val().charAt(4).concat(jQuery('#filter-by-date').val().charAt(5));
	year = jQuery('#filter-by-date').val().charAt(0).concat(jQuery('#filter-by-date').val().charAt(1)).concat(jQuery('#filter-by-date').val().charAt(2)).concat(jQuery('#filter-by-date').val().charAt(3));
	
	if(jQuery('#filter-by-date').val() != "0"){
		return new Date(year, month + 1, 0);
	}

	return '';
}