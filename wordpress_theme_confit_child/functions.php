<?php
    function register_sheepcreek_menus() {
        register_nav_menu('members-menu',__( 'Members Menu' ));
        register_nav_menu('members-logged-out-menu',__( 'Members Logged Out Menu' ));
    }
    add_action( 'init', 'register_sheepcreek_menus' );
    
    function add_login_logout_link($items, $args){
        if($args->menu->slug == 'members-menu'  ){
            $link = '<a href="' . wp_logout_url() . '" title="' .  __( 'Logout' ) .'">' . __( 'Logout' ) . '</a>';
            return $items.= '<li id="log-in-out-link" class="menu-item menu-type-link">'. $link . '</li>';
        }
        return $items;
    }
    add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
    
    function rkk_redirect_admin(){
        if ( ! current_user_can( 'edit_posts' ) ){
            wp_redirect( site_url() );
            exit;
        }
    }
    add_action( 'admin_init', 'rkk_redirect_admin' );
    
    function rkk_disable_admin_bar() {
        if( ! current_user_can('edit_posts') )
            add_filter('show_admin_bar', '__return_false');
    }
    add_action( 'after_setup_theme', 'rkk_disable_admin_bar' );
    
    function sheepcreek_user_login_redirect_to($redirect_to){
        $redirect_to = "/message-board";
        return $redirect_to;
    }
    add_action( 'bbp_user_login_redirect_to', 'sheepcreek_user_login_redirect_to' );
    
    add_action('wp_logout',create_function('','wp_redirect(home_url());exit();'));
//
//    function sheepcreek_check_for_logged_in(){
//        
//        if (is_bbpress() && !is_user_logged_in() && !preg_match("/message-board-login/", $_SERVER["REQUEST_URI"])){
//            wp_redirect("/message-board-login");
//            exit();
//        }
//    }
//    add_action('bbp_ready', 'sheepcreek_check_for_logged_in');
    
    add_action( 'init', 'register_sheepcreek_property' );
    function register_sheepcreek_property() {
        $labels = array(
            'name' => 'Properties',
            'singular_name' => 'Property',
            'add_new' => 'Add New Property',
            'add_new_item' => 'Add New Property',
            'edit_item' => 'Edit Property',
            'new_item' => 'New Property',
            'view_item' => 'View Property',
            'search_items' => 'Search Propertys',
            'not_found' => 'No Properties found',
            'not_found_in_trash' => 'No Properties found in Trash',
            'parent_item_colon' => 'Parent Properties:',
            'menu_name' => 'Properties',
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Properties',
            'supports' => array( 'title', 'thumbnail', 'revisions' ),
            'taxonomies' => array( 'property_categories'),
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'query_var' => "property",
            'can_export' => true,
            'rewrite' => array('slug' => 'property', 'with_front' => FALSE),
            'public' => true,
            'has_archive' => 'property',
            'capability_type' => 'post'
        );  
        register_post_type( 'property', $args );//max 20 charachter cannot contain capital letters and spaces
    }

    add_action( 'init', 'property_taxonomy');
    function property_taxonomy() {  
        register_taxonomy(  
            'property_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
            'property',        //post type name
            array(  
                'hierarchical' => true,  
                'label' => 'Property Categories',  //Display name
                'query_var' => true,
                'rewrite' => array(
                    'slug' => 'property', // This controls the base slug that will display before each term
                    'with_front' => false // Don't display the category base before 
                )
            )  
        );
    }
    
    add_filter( 'rwmb_meta_boxes', 'sheepcreek_property_register_meta_boxes' );
    function sheepcreek_property_register_meta_boxes( $meta_boxes )
    {
        $prefix = 'sheepcreek-property-';
        // 1st meta box
        $meta_boxes[] = array(
            'id'       => 'property-info',
            'title'    => 'Property Info',
            'pages'    => array( 'property'),
            'context'  => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => 'Phase/Location',
                    'desc'  => 'Phase/Location',
                    'id'    => $prefix . 'phase',
                    'type'  => 'select',
                    'options' => array(
                        "" => "",
                        "Phase I" => "Phase I",
                        "Phase II" => "Phase II", 
                        "Phase III" => "Phase III", 
                        "Phase IV" => "Phase IV", 
                        "PUD I" => "PUD I", 
                        "PUD II" => "PUD II"
                    )
                ),
                array(
                    'name'  => 'Lot Size',
                    'desc'  => 'Lot Size (in acres)',
                    'id'    => $prefix . 'lot_size',
                    'type'  => 'text',
                ),
                array(
                    'name'  => 'Owner(s)',
                    'desc'  => 'Owner\'s Name',
                    'id'    => $prefix . 'owner',
                    'type'  => 'text',
                ),
                array(
                    'name'  => 'Owner\'s Email',
                    'desc'  => 'Owner\'s Email',
                    'id'    => $prefix . 'owner_email',
                    'type'  => 'email',
                ),
                array(
                    'name'  => 'Owner\'s Phone',
                    'desc'  => 'Owner\'s Phone',
                    'id'    => $prefix . 'owner_phone',
                    'type'  => 'text',
                ),
                array(
                    'name'  => 'Agent',
                    'desc'  => 'Agent',
                    'id'    => $prefix . 'agent',
                    'type'  => 'text',
                ),
                array(
                    'name'  => 'Agent Phone',
                    'desc'  => 'Agent Phone',
                    'id'    => $prefix . 'agent_phone',
                    'type'  => 'text',
                ),
                array(
                    'name'  => 'Agent Email',
                    'desc'  => 'Agent Email',
                    'id'    => $prefix . 'agent_email',
                    'type'  => 'email',
                ),
                array(
                    'name'  => 'Description/Notes',
                    'desc'  => 'Description/Notes',
                    'id'    => $prefix . 'description',
                    'type'  => 'textarea',
                )
            )
        );

        return $meta_boxes;
    }