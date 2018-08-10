jQuery(function($) {
    $(document).ready(function(){
        setTimeout(resizeHeader, 250);
        setTimeout(resizeHeader, 500);
        setTimeout(resizeHeader, 1000);
        setTimeout(resizeHeader, 5000);
    });
    $( window ).resize(function() {
    resizeHeader();
    });
    function resizeHeader(){ 
        // check to see if top-menu is set to display none which means 
        // we are less than 980px and don't want to resize the header
        if( $("#top-menu").css("display") != "none" ) {
            var header_height = $( "#main-header" ).height();
            var new_style = '@media (min-width: 981px) { body.divi-supercharger #page-container { padding-top: ' + header_height + 'px !important; } }';
            Replace_Style('dynamic-header', new_style );
        }
    }
    function Replace_Style(element_name, style){
		var new_style = '<style id="' + element_name + '">' + style + '</style>';
        var style_element = $('#' + element_name);
		if ( style_element.length ) {
            style_element.replaceWith( new_style ); 
		} else {
            $( 'head' ).append( new_style ); 
		}
	} 
});
