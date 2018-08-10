<?php
namespace DS_Divi_Supercharger\VideoEmbedding\v1;

if(!class_exists('OembedFilters'))  { class OembedFilters {

  public function __construct() {
    //Filter vimeo videos
    add_filter( 'oembed_fetch_url', array($this, 'ds_edivi_video_embed_fetch_url'), 10, 3 );
  }

  public function ds_edivi_video_embed_fetch_url( $provider, $url, $args ) {
    $this->wl('oembed called' );

    //check if the global options are set
    $ds_edivi_video_options = get_option('ds_edivi_general_settings_options', true)['remove_video_titles'];
    if($ds_edivi_video_options == null || $ds_edivi_video_options == '') { $ds_edivi_video_options = 'default';}


    /** // https://gist.github.com/richaber/746b5aa389ed71b79859
     * Parse the original embed $url for any URL query params.
     *
     * If the query component doesn't exist in $url, will return null. May return false for seriously malformed URLs.
     *
     * @var bool|null|string $php_url_query
     */
    $php_url_query = parse_url( $url, PHP_URL_QUERY );

    /** // https://gist.github.com/richaber/746b5aa389ed71b79859
     * Split the $provider URL on '%3F', the urlencoded equivalent of the question mark (?) character.
     *
     * Should return 2 array members, where $provider_parts['0'] will be the oEmbed provider URL, minus the urlencoded
     * query params, and $provider_parts['1'] will be the urlencoded query params that are not helpful, and they will
     * be replaced by the $php_url_query.
     *
     * @var array $provider_parts
     */
    if ( !empty( $php_url_query ) ) {
      $provider_parts = explode( '%3F', $provider );
    }

    if ( !empty( $provider_parts['1'] ) && $ds_edivi_video_options != 'remove') {
      return $provider;
    }
    /** // https://gist.github.com/richaber/746b5aa389ed71b79859
     * Parse $php_url_query as if it were a URL query string, and store the variables in $query_args array.
     *
     * @var array $query_args
     */
    parse_str( $php_url_query, $query_args );

    $this->wl($query_args);


        if($ds_edivi_video_options == 'remove') {
          $query_args['title'] = 0;
          $query_args['portrait'] = 0;
          $query_args['byline'] = 0;
        }
    $this->wl($query_args);
    // You can find the list of defaults providers in WP_oEmbed::__construct()
    if ( strpos( $provider, 'vimeo.com' ) !== false) {
        // Check the full list of args here: https://developer.vimeo.com/apis/oembed
        if ( isset( $query_args['autoplay'] ) ) {
            $provider = add_query_arg( 'autoplay', absint( $query_args['autoplay'] ), $provider );
        }
        if ( isset( $query_args['color'] ) && preg_match( '/^[a-f0-9]{6}$/i', $query_args['color'] ) ) {
            $provider = add_query_arg( 'color', $query_args['color'], $provider );
        }
        if ( isset( $query_args['portrait'] ) ) {
            $provider = add_query_arg( 'portrait', absint( $query_args['portrait'] ), $provider );
        }
        if ( isset( $query_args['title'] ) ) {
            $provider = add_query_arg( 'title', absint( $query_args['title'] ), $provider );
        }
        if ( isset( $query_args['byline'] ) ) {
            $provider = add_query_arg( 'byline', absint( $query_args['byline'] ), $provider );
        }
        if ( isset( $query_args['maxwidth'] ) ) {
            $provider = add_query_arg( 'maxwidth', absint( $query_args['maxwidth'] ), $provider );
        }
        if ( isset( $query_args['maxheight'] ) ) {
            $provider = add_query_arg( 'maxheight', absint( $query_args['maxheight'] ), $provider );
        }
        if ( isset( $query_args['width'] ) ) {
            $provider = add_query_arg( 'width', absint( $query_args['width'] ), $provider );
        }

    }
    return $provider;
  }


	public function wl ( $log )  {
		if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
						error_log( print_r( $log, true ) );
				} else {
						error_log( $log );
				}
			}
	} // end write_log

  public function ds_edivi_video_embed_scripts() {
    //not needed for Divi
    ?>
    <script type="text/javascript">
      jQuery(function($) {

        $(document).ready(function() {

        	var $all_oembed_videos = $("iframe[src*='youtube'], iframe[src*='vimeo']");
          console.log($all_oembed_videos);
        	$all_oembed_videos.each(function() {

        		$(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );

         	});

        }); //end $(document).ready(function()

      });
    </script>
    <style type="text/css">
    .embed-container {
    	position: relative;
    	padding-bottom: 56.25%;
    	height: 0;
    	overflow: hidden;
    	max-width: 100%;
    }

    .embed-container iframe,
    .embed-container object,
    .embed-container embed {
    	position: absolute;
    	top: 0;
    	left: 0;
    	width: 100%;
    	height: 100%;
    }
  </style>
    <?php

  } //end function ds_edivi_video_embed_scripts()

}} //end class OembedFilters