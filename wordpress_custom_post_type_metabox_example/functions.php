<?php
function aspirabutton_shortcode($atts) {
    extract(shortcode_atts(array(
            'caption' => 'Read More',
            'link' => get_bloginfo('url'),
            'target' => ''
    ), $atts));

	$buttonHTML = '
		<a class="aspiraButton" href="'.$link.'" title="'.$caption.'"';
	if ($target != "") $buttonHTML .= ' target="'.$target.'"';
	$buttonHTML .= '	><span>'.$caption.'</span></a>
	'
	;
	return $buttonHTML;
}
add_shortcode('aspirabutton', 'aspirabutton_shortcode');


include get_stylesheet_directory() . "/custom_post_types/lending_library_post_type.php";
include get_stylesheet_directory() . "/custom_post_types/articles_post_type.php";
include get_stylesheet_directory() . "/custom_post_types/website_resources_post_type.php";
include get_stylesheet_directory() . "/custom_post_types/community_directory_post_type.php";
include get_stylesheet_directory() . "/custom_post_types/newsletter_post_type.php";


add_action( 'wp_enqueue_scripts', 'aspiranet_scripts' );
function aspiranet_scripts(){
    $jquery_scripturl = get_theme_root_uri().'/shared/jquery-1.9.0.js';
    wp_register_script( 'jquery', $jquery_scripturl, "", "", 1 ); 
    wp_enqueue_script( 'jquery' );
    
    $jqueryui_scripturl = get_theme_root_uri().'/shared/jquery-ui.js';
    wp_register_script( 'jqueryui', $jqueryui_scripturl, "jquery", "", 1 ); 
    wp_enqueue_script( 'jqueryui' );
    
    $plugins_scripturl = get_template_directory_uri() . "/javascripts/jquery/plugins.js";
    wp_register_script( 'jquery_plugins', $plugins_scripturl, "jquery", "", 1 ); 
    wp_enqueue_script( 'jquery_plugins' );
    
    $scripturl = get_template_directory_uri() . "/javascripts/site.js?v=1";
    wp_register_script( 'sitescript', $scripturl, "sitescript", "jquery_plugins", 1 ); 
    wp_enqueue_script( 'sitescript' );
    
    $fontawesomeurl = get_template_directory_uri() . "/styles/font-awesome.css";
    wp_register_style( 'fontawesome', $fontawesomeurl); 
    wp_enqueue_style( 'fontawesome' );
    
    $bootstrapurl = array("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css");
    wp_register_style( 'bootstrap', $bootstrapurl); 
    wp_enqueue_style( 'bootstrap' );
    
    $bootstrap_scripturl = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js";
    wp_register_script( 'bootstrap', $bootstrap_scripturl); 
    wp_enqueue_script('bootstrap' );

}

?>