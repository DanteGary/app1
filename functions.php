<?php 
	//necesario para poder usar is_page o is_single
add_action('template_redirect','carga_archivos');
 
function carga_archivos(){
     
    if( is_single(9999)) // tu número de post o slug
    {
        wp_enqueue_script( 'google-api','http://maps.googleapis.com/maps/api/js?key=TU CLAVEAPI&sensor=true', array( 'jquery' ) );
        wp_enqueue_script( 'google-maps',get_bloginfo('stylesheet_directory') . '/js/google-map.js', array( 'google-api' ) );
    }
}