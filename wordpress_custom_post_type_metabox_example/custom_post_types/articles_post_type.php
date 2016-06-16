<?php

add_action( 'init', 'register_article_resources' );
function register_article_resources() {
    $labels = array(
        'name' => _x( 'Articles' ),
        'singular_name' => _x( 'Article' ),
        'add_new' => _x( 'Add New Article' ),
        'add_new_item' => _x( 'Add New Article' ),
        'edit_item' => _x( 'Edit Article' ),
        'new_item' => _x( 'New Article' ),
        'view_item' => _x( 'View Article' ),
        'search_items' => _x( 'Search Articles' ),
        'not_found' => _x( 'No Articles found' ),
        'not_found_in_trash' => _x( 'No Articles found in Trash' ),
        'parent_item_colon' => _x( 'Parent Articles:' ),
        'menu_name' => _x( 'Articles' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Articles',
        'supports' => array( 'title', 'thumbnail', 'revisions' ),
        'taxonomies' => array( 'article_resource_categories'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => "article-resources",
        'can_export' => true,
        'rewrite' => array('slug' => 'article-resources', 'with_front' => FALSE),
        'public' => true,
        'has_archive' => 'article_resources',
        'capability_type' => 'post'
    );  
    register_post_type( 'article_resource', $args );//max 20 charachter cannot contain capital letters and spaces
}

add_action( 'init', 'article_resource_taxonomy');
function article_resource_taxonomy() {  
    register_taxonomy(  
        'article_resource_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'article_resource',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Article Categories',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'article-resources', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );
}

add_filter( 'rwmb_meta_boxes', 'article_resources_register_meta_boxes' );
function article_resources_register_meta_boxes( $meta_boxes )
{
    $prefix = 'aspiranet-article-resource-';
    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'article-info',
        'title'    => 'Article Info',
        'pages'    => array( 'article_resource'),
        'context'  => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name'  => 'File',
                'desc'  => 'File',
                'id'    => $prefix . 'file',
                'type'  => 'file_advanced',
            ),
            array(
                'name'  => 'Web Page',
                'desc'  => 'Web Page',
                'id'    => $prefix . 'web-page',
                'type'  => 'url',
            )
        )
    );

    return $meta_boxes;
}