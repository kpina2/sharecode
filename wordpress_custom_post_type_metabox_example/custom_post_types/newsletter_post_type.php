<?php

add_action( 'init', 'register_newsletter_resources' );
function register_newsletter_resources() {
    $labels = array(
        'name' => _x( 'Newsletters' ),
        'singular_name' => _x( 'Newsletter' ),
        'add_new' => _x( 'Add New Newsletter' ),
        'add_new_item' => _x( 'Add New Newsletter' ),
        'edit_item' => _x( 'Edit Newsletter' ),
        'new_item' => _x( 'New Newsletter' ),
        'view_item' => _x( 'View Newsletter' ),
        'search_items' => _x( 'Search Newsletters' ),
        'not_found' => _x( 'No Newsletters found' ),
        'not_found_in_trash' => _x( 'No Newsletters found in Trash' ),
        'parent_item_colon' => _x( 'Parent Newsletters:' ),
        'menu_name' => _x( 'Newsletters' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Newsletters',
        'supports' => array( 'title', 'thumbnail', 'revisions' ),
//        'taxonomies' => array( 'newsletter_categories'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => "newsletter-resources",
        'can_export' => true,
        'rewrite' => array('slug' => 'newsletter-resources', 'with_front' => FALSE),
        'public' => true,
        'has_archive' => 'newsletter_resources',
        'capability_type' => 'post'
    );  
    register_post_type( 'newsletter_resource', $args );//max 20 charachter cannot contain capital letters and spaces
}

//add_action( 'init', 'newsletter_taxonomy');
//function newsletter_taxonomy() {  
//    register_taxonomy(  
//        'newsletter_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
//        'newsletter_resource',        //post type name
//        array(  
//            'hierarchical' => true,  
//            'label' => 'Newsletter Categories',  //Display name
//            'query_var' => true,
//            'rewrite' => array(
//                'slug' => 'newsletter-resources', // This controls the base slug that will display before each term
//                'with_front' => false // Don't display the category base before 
//            )
//        )  
//    );  
//}


add_filter( 'rwmb_meta_boxes', 'newsletter_resources_register_meta_boxes' );
function newsletter_resources_register_meta_boxes( $meta_boxes )
{
    $year_options = array();
    $year = date("Y");
    $years = range($year + 1, 2007);
    foreach($years as $year){
        $year_options[$year] = $year;
    }
    $prefix = 'aspiranet-newsletter-resource-';
    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'newsletter-info',
        'title'    => 'Newsletter Info',
        'pages'    => array( 'newsletter_resource'),
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
                'name'  => 'Quarter',
                'desc'  => 'Quarter',
                'id'    => $prefix . 'quarter',
                'type'  => 'select',
                'options' => array(
                    0 => "Winter",
                    1 => "Spring",
                    2 => "Summer",
                    3 => "Fall"
                )
            ),
            array(
                'name'  => 'Year',
                'desc'  => 'Year',
                'id'    => $prefix . 'year',
                'type'  => 'select',
                'options' =>$year_options,
                'multiple' => false,
                'placeholder' => "choose year..."
            ),
        )
    );

    return $meta_boxes;
}