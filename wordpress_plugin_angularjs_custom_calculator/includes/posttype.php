<?php
// Custom Post Type
add_action( 'init', 'create_fwa_calculator_post_type' );
function create_fwa_calculator_post_type(){
    register_post_type( 'fwa_calculator', 
        array(
            'labels' => array(
                'name' => 'FWA Calculators',
                'singular_name' => 'FWA Calculator',
                'add_new' => 'Add Calculator',
                'add_new_item' => 'Add Calculator',
                'edit' => 'Edit',
                'edit_item' => 'Edit Calculator',
                'new_item' => 'New Calculator',
                'view' => 'View',
                'view_item' => 'View Calculator',
                'search_items' => 'Search Calculator',
                'not_found' => 'No Calculators Found',
                'not_found_in_trash' => 'No Calculators found in Trash',
                'parent' => 'Calculator'
            ),
            'public' => true,
            'rewrite' => array('slug' => 'fwa-calculator'),
            'menu_position' => 20,
            'supports' => array( 'title',(WP_DEBUG == true ? 'custom-fields' : '')),
            'taxonomies' => array(),
            'hierarchical' => true,
			
            // 'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'menu_icon' => '',
            'has_archive' => true,
            'show_in_nav_menus'   => true,
            'show_in_menu'        => TRUE
        )      
    );
}