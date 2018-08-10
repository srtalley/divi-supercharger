/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {
	console.log("EHLP");
	wp.customize( 'ds_edivi_general_href_hover_color', function( value ) {
			console.log("valued");
				console.log(value);
		value.bind( function( new_value ) {
			console.log("new valued");
				console.log(new_value);
			style = 'body.divi-supercharger a:hover { color: ' + new_value + '; }'; // build the style element
			el =  'ds_edivi_general_href_hover_color'; // look for a matching style element that might already be there
			Replace_Style(el, style);
		});
	});

	wp.customize( 'ds_edivi_footer_href_hover_color', function( value, extra ) {
		value.bind( function( new_value ) {
			var style, el;
			style = 'body.divi-supercharger #footer-info a:hover { color: ' + new_value + '; }'; // build the style element
			el =  'ds_edivi_footer_href_hover_color'; // look for a matching style element that might already be there
			Replace_Style(el, style);
		} );
	});

	wp.customize( 'ds_edivi_slider_dot_inactive_color', function( value, extra ) {
		value.bind( function( new_value ) {
			var style, el;
			style = 'body.divi-supercharger .et-pb-controllers a { background-color: ' + new_value + '; }'; // build the style element
			el =  'ds_edivi_slider_dot_inactive_color'; // look for a matching style element that might already be there
			Replace_Style(el, style);
		} );
	});

	wp.customize( 'ds_edivi_slider_dot_active_color', function( value, extra ) {
		value.bind( function( new_value ) {
			var style, el;
			style = 'body.divi-supercharger .et-pb-controllers a.et-pb-active-control { background-color: ' + new_value + ' !important; }'; // build the style element
			el =  'ds_edivi_slider_dot_inactive_color'; // look for a matching style element that might already be there
			Replace_Style(el, style);
		} );
	});

	wp.customize( 'ds_edivi_submenu_background_hover_color', function( value, extra ) {
		value.bind( function( new_value ) {
			var style, el;
			style = 'body.divi-supercharger .et_mobile_menu li a:hover, .nav ul li a:hover { background-color: ' + new_value + ' !important; }'; // build the style element
			el =  'ds_edivi_slider_dot_inactive_color'; // look for a matching style element that might already be there
			Replace_Style(el, style);
		} );
	});

	function Replace_Style(element_name, style){
		var new_style = '<style id="' + element_name + '">' + style + '</style>';
    	var style_element = $('#' + element_name);
		if ( style_element.length ) {
			style_element.replaceWith( new_style ); // style element already exists, so replace it
		} else {
			$( 'head' ).append( new_style ); // style element doesn't exist so add it
		}
	} //end function Replace_Style

})( jQuery );
