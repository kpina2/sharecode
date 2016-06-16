<?php

add_action( 'init', 'register_lending_library' );
function register_lending_library() {
    $labels = array(
        'name' => _x( 'Lending Library Books' ),
        'singular_name' => _x( 'Lending Library Book' ),
        'add_new' => _x( 'Add New Book' ),
        'add_new_item' => _x( 'Add New Lending Library Book' ),
        'edit_item' => _x( 'Edit Book' ),
        'new_item' => _x( 'New Book' ),
        'view_item' => _x( 'View Book' ),
        'search_items' => _x( 'Search Lending Library' ),
        'not_found' => _x( 'No Lending Library Books found' ),
        'not_found_in_trash' => _x( 'No Lending Library Books found in Trash' ),
        'parent_item_colon' => _x( 'Parent Lending Library Books:' ),
        'menu_name' => _x( 'Lending Library Books' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Lending Library Books',
        'supports' => array( 'title', 'thumbnail', 'revisions' ),
        'taxonomies' => array( 'lending_library_categories'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => "lending-library-book",
        'can_export' => true,
        'rewrite' => array('slug' => 'lending-library-books', 'with_front' => FALSE),
        'public' => true,
        'has_archive' => 'lending_library_books',
        'capability_type' => 'post'
    );  
    register_post_type( 'lending_library_book', $args );//max 20 charachter cannot contain capital letters and spaces
}

add_action( 'init', 'lending_library_taxonomy');
function lending_library_taxonomy() {  
    register_taxonomy(  
        'lending_library_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'lending_library_book',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Book Categories',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'lending-library', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
}

add_filter( 'rwmb_meta_boxes', 'lending_library_register_meta_boxes' );
function lending_library_register_meta_boxes( $meta_boxes )
{
    $prefix = 'aspiranet-lending-library-book-';
    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'book-info',
        'title'    => 'Book Info',
        'pages'    => array( 'lending_library_book'),
        'context'  => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name'  => 'Subtitle',
                'desc'  => 'Subtitle',
                'id'    => $prefix . 'subtitle',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Author',
                'desc'  => 'Author',
                'id'    => $prefix . 'author',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Publisher',
                'desc'  => 'Publisher',
                'id'    => $prefix . 'publisher',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Year',
                'desc'  => 'Year',
                'id'    => $prefix . 'year',
                'type'  => 'text',
            ),
            array(
                'name'  => 'Description',
                'desc'  => 'description',
                'id'    => $prefix . 'descriptions',
                'type'  => 'textarea',
            ),
            array(
                'name'  => 'Request Link',
                'desc'  => 'This is the link that will initiate a new email with the title of the book in the Subject line',
                'id'    => $prefix . 'link',
                'type'  => 'text',
            ),
        )
    );

    return $meta_boxes;
}