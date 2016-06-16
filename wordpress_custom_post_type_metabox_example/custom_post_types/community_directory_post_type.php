<?php

add_action( 'init', 'register_community_resource_directory' );
function register_community_resource_directory() {
    $labels = array(
        'name' => _x( 'Community Contact Directory' ),
        'singular_name' => _x( 'Community Contact' ),
        'add_new' => _x( 'Add New Contact' ),
        'add_new_item' => _x( 'Add Contact' ),
        'edit_item' => _x( 'Edit Contact' ),
        'new_item' => _x( 'New Contact' ),
        'view_item' => _x( 'View Contact' ),
        'search_items' => _x( 'Search Community Contact Directory' ),
        'not_found' => _x( 'No Community Contacts found' ),
        'not_found_in_trash' => _x( 'No Community Contacts found in Trash' ),
        'parent_item_colon' => _x( 'Parent Community Contact:' ),
        'menu_name' => _x( 'Community Contact Directory' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Community Contact Directory',
        'supports' => array( 'title', 'thumbnail', 'revisions' ),
        'taxonomies' => array( 'community_contact_categories'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => "community-contacts",
        'can_export' => true,
        'rewrite' => array('slug' => 'community-contacts', 'with_front' => FALSE),
        'public' => true,
        'has_archive' => 'community_contacts',
        'capability_type' => 'post'
    );  
    register_post_type( 'community_contact', $args );//max 20 charachter cannot contain capital letters and spaces
}

add_action( 'init', 'community_resource_directory');
function community_resource_directory() {  
    register_taxonomy(  
        'community_contact_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'community_contact',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Contact Categories',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'community-contacts', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
}

add_filter( 'rwmb_meta_boxes', 'community_resource_directory_register_meta_boxes' );
function community_resource_directory_register_meta_boxes( $meta_boxes )
{
    $prefix = 'aspiranet-community-contact-';
    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'community-resource-contact',
        'title'    => 'Community Resource Contact',
        'pages'    => array( 'community_contact'),
        'context'  => 'normal',
        'priority' => 'high',
        'fields' => array(
           array(
                'name'  => 'Address',
                'desc'  => 'address',
                'id'    => $prefix . 'address',
                'type'  => 'textarea',
            ),
            array(
                'name'  => 'Phone Number',
                'desc'  => 'phone',
                'id'    => $prefix . 'phone',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Website',
                'desc'  => 'website',
                'id'    => $prefix . 'website',
                'type'  => 'url',
            ),
        )
    );

    return $meta_boxes;
}