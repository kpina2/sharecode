<?php
    
    add_action( 'widgets_init', 'register_goodmarketing_social_icons_widget' );
    function register_goodmarketing_social_icons_widget() {
        register_widget( 'Gm_social_icons_Widget' );
    }
    class Gm_social_icons_Widget extends WP_Widget {
        function Gm_social_icons_Widget() {
            $widget_ops = array( 'classname' => 'gm-social', 'description' => __('Setup Social Icons for Goodmarketing Site', 'gm-social') );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'gm-social' );
            $this->WP_Widget( 'gm-social', __('Goodmarketing Social Icons Widget', 'gm-social'), $widget_ops, $control_ops );
        }
        
        	
        function widget( $args, $instance ){
            extract( $args );
            $facebook = $instance['facebook'];
            $twitter = $instance['twitter'];
            $email = $instance['email'];
           
            echo $before_widget;

            if ( $facebook ){
                ?>
                <a class="gm-social-img" href="<?php echo $facebook; ?>">
                    <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/header-fb-icon.png">
                </a>
                <?php
            }
            
           
            if ( $twitter ){
                ?>
                <a class="gm-social-img" href="<?php echo $twitter; ?>">
                    <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/header-twitter-icon.png">
                </a>
                <?php
            }
            
            ?>
                <a class="gm-social-img" target='_blank' href="<?php echo get_site_url(); ?>/feed">
                    <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/header-rss-icon.png">
                </a>
            <?php
            
            //Display the name
            if ( $email ){
            ?>
                <a class="gm-social-img" href="mailto:<?php echo $email; ?>">
                    <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/header-email-icon.png">
                </a>
            <?php
            }

            echo $after_widget;
        }
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
//            $instance['title']="Social Icons";
            //Strip tags from title and name to remove HTML
            $instance['twitter'] = strip_tags( $new_instance['twitter'] );
            $instance['facebook'] = strip_tags( $new_instance['facebook'] );
            $instance['email'] = strip_tags( $new_instance['email'] );

            return $instance;
        }
        function form($instance){
            if( $instance) {
                     $twitter = esc_attr($instance['twittter']);
        	     $facebook = esc_attr($instance['facebook']);
        	     $email = esc_attr($instance['email']);
        	    
        	} else {
                    $twitter = '';
        	     $facebook = '';
        	     $email = '';
        	}
            ?>
                <p>
                    <label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook Page URL:', 'facebook'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" style="width:100%;" />
                </p>
                
                 <p>
                    <label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('Twitter Page URL:', 'twitter'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php echo $instance['twitter']; ?>" style="width:100%;" />
                </p>
                
                <p>
                    <label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Contact Email:', 'email'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" style="width:100%;" />
                </p>
            <?php
        }
    }
    
    if ( function_exists ('register_sidebar')) { 
        $args = array(
            'name'          => "Front Page Sidebar",
            'id'            => "sidebar-front-page"
        );
        register_sidebar($args);
    }
    
    if ( function_exists ('register_sidebar')) { 
        $args = array(
            'name'          => "Post Content Sidebar",
            'id'            => "sidebar-post"
        );
        register_sidebar($args);
    }
    
    if ( function_exists ('register_sidebar')) { 
        $args = array(
            'name'          => "Page Content Sidebar",
            'id'            => "sidebar-page"
        );
        register_sidebar($args);
    }
    
    if ( function_exists ('register_sidebar')) { 
        $args = array(
            'name'          => "Header Sidebar",
            'id'            => "sidebar-header"
        );
        register_sidebar($args);
    }

    function myplugin_settings() {  
        // Add tag metabox to page
        register_taxonomy_for_object_type('post_tag', 'page'); 
        // Add category metabox to page
        register_taxonomy_for_object_type('category', 'page');  
    }
    // Add to the admin_init hook of your theme functions.php file 
    add_action( 'admin_init', 'myplugin_settings' );

// DEFAULT IS 55
function custom_excerpt_length( $length ) {
	return 130;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


//function new_excerpt_more($output) {
//    return $output . '<p><a href="'. get_permalink() . '">' . 'Read this full post' . '</a></p>';
//}
//add_filter('get_the_excerpt', 'new_excerpt_more');

function custom_wp_trim_excerpt($text) {
    $raw_excerpt = $text;
    if ( '' == $text ) {
        //Retrieve the post content.
        $text = get_the_content('');

        //Delete all shortcode tags from the content.
        $text = strip_shortcodes( $text );

        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);

        $allowed_tags = '<p>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
        $text = strip_tags($text, $allowed_tags);

        $excerpt_word_count = 55; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
        $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);

        $excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
        $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
        $text = $text . '<p class="readmore-link"><a href="'. get_permalink() . '">' . 'Read this full post' . '</a></p>';
    }
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');

function twentyfourteen_post_nav($same_cat = false) {
    
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
        
	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	
            <h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentyfourteen' ); ?></h1>
            <div class="nav-links">
                    <?php // if ( is_attachment() ) : ?>
<!--                            previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'twentyfourteen' ) );-->
                    <?php // else : ?>
                            <?php // if(get_previous_posts_link()): ?>
                                <div class="nav-link prev-link">
                                    <?php previous_post_link( '%link', __( 'Read Previous Post', 'twentyfourteen' ), $same_cat ); ?>
                                </div>
                            <?php // endif; ?>
                            <?php // if(get_next_posts_link()): ?>
                                <div class="nav-link next-link">
                                    <?php next_post_link( '%link', __( 'Read Next Post', 'twentyfourteen' ), $same_cat ); ?>
                                </div>
                            <?php // endif; ?>
                        <?php // endif; ?>
            </div><!-- .nav-links -->
	
	<?php
}
?>
