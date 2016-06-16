<?php

add_action( 'init', 'register_web_resources' );
function register_web_resources() {
    $labels = array(
        'name' => _x( 'Website Resources' ),
        'singular_name' => _x( 'Website Resource' ),
        'add_new' => _x( 'Add New Website' ),
        'add_new_item' => _x( 'Add New Website' ),
        'edit_item' => _x( 'Edit Website' ),
        'new_item' => _x( 'New Website' ),
        'view_item' => _x( 'View Website' ),
        'search_items' => _x( 'Search Website Resources' ),
        'not_found' => _x( 'No Website Resources found' ),
        'not_found_in_trash' => _x( 'No Website Resources found in Trash' ),
        'parent_item_colon' => _x( 'Parent Website Resources:' ),
        'menu_name' => _x( 'Website Resources' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Website Resources',
        'supports' => array( 'title', 'thumbnail', 'revisions' ),
        'taxonomies' => array( 'web_resource_categories'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => "website-resources",
        'can_export' => true,
        'rewrite' => array('slug' => 'website-resources', 'with_front' => FALSE),
        'public' => true,
        'has_archive' => 'website_resources',
        'capability_type' => 'post'
    );  
    register_post_type( 'website_resource', $args );//max 20 charachter cannot contain capital letters and spaces
}

add_action( 'init', 'web_resources_taxonomy');
function web_resources_taxonomy() {  
    register_taxonomy(  
        'web_resource_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'website_resource',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Website Categories',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'website-resources', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
}

add_filter( 'rwmb_meta_boxes', 'web_resources_register_meta_boxes' );
function web_resources_register_meta_boxes( $meta_boxes )
{
    $prefix = 'aspiranet-website-resource-';
    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'website-info',
        'title'    => 'Website Info',
        'pages'    => array( 'website_resource'),
        'context'  => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name'  => 'URL',
                'desc'  => 'URL',
                'id'    => $prefix . 'url',
                'type'  => 'url',
            ),
            array(
                'name'  => 'Notes',
                'desc'  => 'Notes',
                'id'    => $prefix . 'notes',
                'type'  => 'textarea',
            ),
        )
    );

    return $meta_boxes;
}