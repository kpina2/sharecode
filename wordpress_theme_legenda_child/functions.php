<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

wp_enqueue_script( "kpm_theme_script", get_stylesheet_directory_uri() . "/js/custom.js", array(), "", true );

// ADD TEAM POST TYPE
add_action( 'init', 'create_mwa_team_post_type' );
function create_mwa_team_post_type(){
    register_post_type( 'mwa_team', 
      array(
            'labels' => array(
                'name' => ' Teams',
                'singular_name' => 'Team',
                'add_new' => 'Add Team',
                'add_new_item' => 'Add Team',
                'edit' => 'Edit',
                'edit_item' => 'Edit Team',
                'new_item' => 'New Team',
                'view' => 'View',
                'view_item' => 'View Team',
                'search_items' => 'Search Team',
                'not_found' => 'No  Teams Found',
                'not_found_in_trash' => 'No  Teams found in Trash',
                'parent' => ' Team'
            ),
            'public' => true,
            'rewrite' => array('slug' => 'team'),
            'menu_position' => 20,
            'supports' => array( 'title', 'thumbnail', 'revisions', (WP_DEBUG == true ? 'custom-fields' : '')),
            'taxonomies' => array(),
            'hierarchical' => true,
			
            // 'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'menu_icon' => '/wp-content/themes/legenda-child/mwa-icon-darker.png',
            'has_archive' => true,
            'show_in_nav_menus'   => true,
            'show_in_menu'        => TRUE
        )      
    );
}

// ADD PASSWORD TO TEAM POST TYPE
add_filter( 'rwmb_meta_boxes', 'mwa_team_register_meta_boxes' );
function mwa_team_register_meta_boxes( $meta_boxes ){
    $prefix = 'mwa_team_';
    $meta_boxes[] = array(
        'title'      => __( 'Password Protection', 'textdomain' ),
        'post_types' => 'mwa_team',
        'fields'     => array(
            array(
                'id'   => $prefix . 'password',
                'name' => __( 'Password', 'textdomain' ),
                'type' => 'text',
            )
        )
    ); 
    
    $meta_boxes[] = array(
        'title'      => __( 'Order Total for Free Shipping', 'textdomain' ),
        'post_types' => 'mwa_team',
        'fields'     => array(
            array(
                'id'   => $prefix . 'freetotal',
                'name' => __( 'Total for Free Shipping', 'textdomain' ),
                'type' => 'text'
            )
        )
    ); 
    
     $meta_boxes[] = array(
        'title'      => __( 'Free Shipping Address', 'textdomain' ),
        'post_types' => 'mwa_team',
        'fields'     => array(
            array(
                'id'   => $prefix . 'freeaddress',
                'name' => __( 'Address', 'textdomain' ),
                'type' => 'text'
            ),
            array(
                'id'   => $prefix . 'freecity',
                'name' => __( 'City', 'textdomain' ),
                'type' => 'text'
            ),
            array(
                'id'   => $prefix . 'freestate',
                'name' => __( 'State', 'textdomain' ),
                'type' => 'text'
            ),
            array(
                'id'   => $prefix . 'freezip',
                'name' => __( 'Zip', 'textdomain' ),
                'type' => 'text'
            ),
            'clone' => true
        )
    ); 
    
    $meta_boxes[] = array(
        'title'      => __( 'Team Logo', 'textdomain' ),
        'post_types' => 'mwa_team',
        'fields'     => array(
            array(
                'id'   => $prefix . 'logo',
                'name' => __( 'Team Logo', 'textdomain' ),
                'type' => 'image_advanced',
            )
        )
    ); 
    return $meta_boxes;
}


// ADD TEAM DROP DOWN TO WOOCOMMERCE
function woo_add_wma_team_field(){
    global $woocommerce, $post;
    echo '<div class="options_group">';
        $args=array(
            'post_type' => 'mwa_team',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $teams_array = get_posts( $args ); 
        
        foreach($teams_array as $team){
            $teams_options[$team->ID] = $team->post_title;
        }
        
        asort($teams_options);
        $teams_options = array("none"=>"No Team") + $teams_options; 
        
        woocommerce_wp_select( 
            array( 
                'id'      => 'wma_team_option', 
                'label'   => __( 'Team', 'woocommerce' ), 
                'options' => $teams_options
                )
        );
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_wma_team_field' );

// SAVE TEAM OPTION TO PRODUCT
function woo_add_wma_team_field_save( $post_id ){
    $woocommerce_select = $_POST['wma_team_option'];
    if( !empty( $woocommerce_select ) ){
        update_post_meta( $post_id, 'wma_team_option', esc_attr( $woocommerce_select ) );
    }
}
add_action( 'woocommerce_process_product_meta', 'woo_add_wma_team_field_save' );

// ADD TEAM POST TYPES TO NAV MENU
function mwa_custom_page_menu($menu, $args){
    return $menu;
}
add_filter( 'wp_page_menu', 'mwa_custom_page_menu' ,10,2 );

function get_team_user(){
    global $post;
    
    if($_SESSION['team_page_access'][$post->ID]){
        return true;
    }else{
        return false;
    }
}
function team_pw_test(){
    session_start();
    global $post;
   
    if ( $post->post_type == 'mwa_team') {
        
        $password = rwmb_meta( 'mwa_team_password', array(), $post->ID);
        
        if(!empty($_POST['mwa_password'])){
            if($_POST['mwa_password'] == $password){
                $_SESSION['team_page_access'][$post->ID] = true;
                if(!empty($_SESSION['product_page_redirect'])){
                    header("Location: " . $_SESSION['product_page_redirect']);
                    exit;
                }
            } 
        }
        
        if(!empty($_POST['mwa_logout'])){
            
            unset($_SESSION['team_page_access'][$post->ID]);
        }
    }
//    
    // if product check so see if it's related to a team
    if ( $post->post_type == 'product') {
        session_start();

        $team_option_id = get_post_meta( $post->ID, 'wma_team_option', 1 );
        if(!empty($team_option_id) && $team_option_id != "none"){
            $wma_team_post = get_post( $team_option_id );
            
            if($_SESSION['team_page_access'][$wma_team_post->ID] == true){
                unset($_SESSION['product_page_redirect']);
            }else{
                $_SESSION['product_page_redirect'] = $_SERVER['REQUEST_URI'];
                header("Location: " . get_permalink($wma_team_post->ID));
                exit;
            }   
        }else{
            unset($_SESSION['product_page_redirect']);
        }
    }
}
add_action( 'template_redirect', 'team_pw_test' );



// TAP INTO WOOCOMMERCE QUERY TO MODIFY THE PRODUCT LOOP REMOVING TEAM ITEMS
function wma_product_query($q){
    // get all the team IDs
    $args=array(
        'post_type' => 'mwa_team',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $teams_array = get_posts( $args ); 
    $team_id_array = array();
    foreach($teams_array as $team){
        array_push($team_id_array, $team->ID);
    }
    
//    remove team ids the user has access to
    session_start();
    if(isset($_SESSION['team_page_access']) && is_array($_SESSION['team_page_access'])){
        $allowed_team_ids = array_keys($_SESSION['team_page_access']);
    }else{
        $allowed_team_ids = array();
    }
    $value_array = array_diff($team_id_array, $allowed_team_ids);
    
//    add a meta query the prevents access to tema items
    $meta_query = $q->get( 'meta_query' );
    $meta_query[] = array(
                    'key'       => 'wma_team_option',
                    'compare'   => 'NOT IN',
                    'value'     => $value_array
                );
    $q->set( 'meta_query', $meta_query );
}
add_action( 'woocommerce_product_query', 'wma_product_query' );

function wma_related_product_query($q){
     unset($q['post__in']);
    // get teams
    $args=array(
        'post_type' => 'mwa_team',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $teams_array = get_posts( $args );
    $team_id_array = array();
    foreach($teams_array as $team){
        array_push($team_id_array, $team->ID);
    }
    
    // remove teams that the user is logged in
   if(isset($_SESSION['team_page_access']) && is_array($_SESSION['team_page_access'])){
        $allowed_team_ids = array_keys($_SESSION['team_page_access']);
    }else{
        $allowed_team_ids = array();
    }
    $value_array = array_diff($team_id_array, $allowed_team_ids);
    
    // get products that are not associcated with a team
    $args2=array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'       => 'wma_team_option',
                'compare'   => 'NOT IN',
                'value'     => $value_array
            )
        )
    );
    $products_array = get_posts( $args2 );
    
    $product_id_array = array();
    foreach($products_array as $product){
        array_push($product_id_array, $product->ID);
    }
    
    $q['post__in'] = $product_id_array; 
    return $q;
}
add_filter("woocommerce_related_products_args", 'wma_related_product_query');

function filter_woocommerce_product_categories_widget_args($list_args ){
    include_once("kpm-custom-cat-list-walker.php");
    $list_args['walker'] = new KPM_Custom_Cat_List_Walker;
    return $list_args;
}
add_filter( 'woocommerce_product_categories_widget_args', 'filter_woocommerce_product_categories_widget_args', 10, 1 ); 


function kpm_override_woocommerce_widgets() {
    if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {
        unregister_widget( 'WC_Widget_Layered_Nav' );

        include_once("kpm-custom-widget-layered-nav.php");

        register_widget( 'KPM_Custom_Widget_Layered_Nav' );
    }
}
add_action( 'widgets_init', 'kpm_override_woocommerce_widgets', 15 );

$viewable_products = array();
add_action("init", "setup_viewable_products");
function setup_viewable_products(){
    // get teams
    $args=array(
        'post_type' => 'mwa_team',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $teams_array = get_posts( $args );
    $team_id_array = array();
    foreach($teams_array as $team){
        array_push($team_id_array, $team->ID);
    }

    // remove teams that the user is logged in
    if(isset($_SESSION['team_page_access']) && is_array($_SESSION['team_page_access'])){
        $allowed_team_ids = array_keys($_SESSION['team_page_access']);
    }else{
        $allowed_team_ids = array();
    }
    $value_array = array_diff($team_id_array, $allowed_team_ids);

    // get products that are not associcated with a team
    $args2=array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'       => 'wma_team_option',
                'compare'   => 'NOT IN',
                'value'     => $value_array
            )
        )
    );
    $products_array = get_posts( $args2 );

    $product_id_array = array();
    foreach($products_array as $product){
        array_push($product_id_array, $product->ID);
    }
    global $viewable_products;
    $viewable_products = $product_id_array;
}

add_filter('widget_text', 'do_shortcode');
add_shortcode('mwa_team_filter', 'mwa_team_filter_go');
function mwa_team_filter_go(){
    $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'wma_team_option',
                    'value' => get_the_ID(), // this is the TEAM ID!!
                )
            )
    );
    $filter_product_loop = new WP_Query();
    $results = $filter_product_loop->query($args);
 
    $category_array = array();
    
    $filters_choices = array("NCA Categories" => 'pa_nca-categories', "Brand" => 'pa_brand', "Color" => 'pa_color');
    $other_filters_array = array();
    $clear_filters_string = ""; 
    
    foreach($results as $product){
        $product_cats = get_the_terms( $product->ID, 'product_cat' );
        if(!empty($product_cats[0])){
            $category_array[$product_cats[0]->name] = $product_cats[0]->name;
        }
        if(isset($_GET['category_filter'])){
            $url = strtok($_SERVER["REQUEST_URI"],'?');
            $clear_filters_string = "<ul><li class='chosen'><a title='Clear Filters' href='$url'>Clear Filters</a></li></ul>";
        }
        
        foreach($filters_choices as $filter_name => $slug){
            if(isset($_GET[$slug])){
                $url = strtok($_SERVER["REQUEST_URI"],'?');
                $clear_filters_string = "<ul><li class='chosen'><a title='Clear Filters' href='$url'>Clear Filters</a></li></ul>";
            }
            $filter_values = get_the_terms( $product->ID, $slug );
            if(!empty($filter_values)){
                foreach($filter_values as $filter_value){
                    $other_filters_array[$filter_name][$filter_value->name] = $filter_value->name;
                }
            }
        }
    }
    
    $category_filters = "";
    if(!empty($category_array)){
        $category_filters = "<div id='woocommerce_product_categories-2' class='sidebar-widget woocommerce widget_product_categories'><h4 class='widget-title'>Categories</h4>";
        $category_filters .= "<ul class='product-categories mwa_team_sidebar_filter'>";
           foreach($category_array as $id => $category){
               $href_id = urlencode ($id);
               $category_filters .= "<li class='cat-item'><a href='?category_filter=$href_id'>" . $category . "</a></li>";
           }
        $category_filters .= "</ul></div>";
    }
    
    $other_filters = "";
    if(!empty($other_filters_array)){
        foreach($other_filters_array as $filter_name => $other_filter){
            if(!empty($other_filter)){
                $other_filters .= "<div id='woocommerce_product_categories-2' class='sidebar-widget woocommerce widget_product_categories'><h4 class='widget-title'>Filter by $filter_name</h4>";
                $other_filters .= "<ul class='product-categories mwa_team_sidebar_filter'>";
              
                foreach($other_filter as $id => $filter_link){
                    $href_id = urlencode ($id);
                    $slug = $filters_choices[$filter_name];
                    $other_filters .= "<li class='cat-item'><a href='?$slug=$href_id'>" . $filter_link . "</a></li>";
                }
                $other_filters .= "</ul></div>";
            }
        }
    }
    
    $filters_out .= $clear_filters_string . $category_filters . $other_filters;
    return $filters_out;
}


add_filter( 'woocommerce_checkout_fields' , 'mwa_add_survey_question_to_checkout' );
function mwa_add_survey_question_to_checkout($fields){
    $fields['billing']['mwa_team_affiliated']  = array(
        'label'     => __('Swim Team Affiliation: '),
//        'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
        'type' => "text",
        'required'  => false,
        'clear'     => true,
    );
    
    if(isset($_SESSION['team_page_access']) && is_array($_SESSION['team_page_access'])){
        $team_ids = array_keys($_SESSION['team_page_access']);
    }else{
        $team_ids = array();
    }
    
    $team_freeaddress = rwmb_meta( 'mwa_team_freeaddress', array(), $team_ids[0]);
    if(!empty($team_freeaddress)){
        $team_freecity = rwmb_meta( 'mwa_team_freecity', array(), $team_ids[0]);
        $team_freestate = rwmb_meta( 'mwa_team_freestate', array(), $team_ids[0]);
        $team_freezip = rwmb_meta( 'mwa_team_freezip', array(), $team_ids[0]);
        
        $fields['shipping']['mwa_team_freeaddress'] = array(
            'type' => "text",
            'required'  => false,
            'clear'     => true,
            'class' => array('hide_me'),
            'default' => $team_freeaddress
        ); 
        $fields['shipping']['mwa_team_freecity'] = array(
            'type' => "text",
            'required'  => false,
            'clear'     => true,
            'class' => array('hide_me'),
            'default' => $team_freecity
        ); 
        $fields['shipping']['mwa_team_freestate'] = array(
            'type' => "text",
            'required'  => false,
            'clear'     => true,
            'class' => array('hide_me'),
            'default' => $team_freestate
        ); 
        $fields['shipping']['mwa_team_freezip'] = array(
            'type' => "text",
            'required'  => false,
            'clear'     => true,
            'class' => array('hide_me'),
            'default' => $team_freezip
        );
        
        $fields['shipping']['mwa_team_use_freeaddress'] = array(
            'type' => "text",
            'required'  => false,
            'clear'     => true,
            'class' => array('hide_me'),
            'default' => true
        );
                
        $fields['shipping']['mwa_team_freeaddress_string'] = $team_freeaddress . " " . $team_freecity . ", " . $team_freestate . " " . $team_freezip;
    }
    
    
    return $fields;
}


add_action( 'woocommerce_checkout_update_order_meta', 'mwa_checkout_field_update_order_meta' );
function mwa_checkout_field_update_order_meta( $order_id ) {
    session_start();
    if(!empty($_SESSION['team_page_access'])){
        $affiliated_team_value = "";
        foreach($_SESSION['team_page_access'] as $key => $value){
            $wma_team_post = get_post( $key );
            if(!empty($wma_team_post->post_title)){
                $affiliated_team_value .= $wma_team_post->post_title . " ";
            }
        }
        $result = update_post_meta( $order_id, 'MWA Team from Login', trim($affiliated_team_value) );
    }
    
    if ( ! empty( $_POST['mwa_team_affiliated'] ) ) {
        $result = update_post_meta( $order_id, 'MWA Affiliated Team', sanitize_text_field( $_POST['mwa_team_affiliated'] ) );
    }
}

add_filter('woocommerce_email_customer_details_fields', 'mwa_display_email_order_user_meta', 30, 3 );
function mwa_display_email_order_user_meta($fields, $sent_to_admin, $order ){
//   var_dump($fields);
    
    $mwa_team_affiliated_from_login = get_post_meta ( $order->id, 'MWA Team from Login', true );
    if(!empty($mwa_team_affiliated_from_login)){
        $fields['mwa_team_affiliated_from_login'] = array(
            "label" => "MWA Team from Login",
            "value" => $mwa_team_affiliated_from_login
        );
    }
    
    $mwa_team_affiliated = get_post_meta ( $order->id, 'MWA Affiliated Team', true );
    if(!empty($mwa_team_affiliated)){
        $fields['mwa_team_affiliated'] = array(
            "label" => "MWA Affiliated Team",
            "value" => $mwa_team_affiliated
        );
    }
   return $fields;
}

add_action('woocommerce_before_cart_table', 'discount_when_produts_in_cart');
function discount_when_produts_in_cart(){
    $chosen_shipping_methods = $woocommerce->session->get( 'chosen_shipping_methods');
    $chosen_shipping_methods[0] = "free_shipping";
    $woocommerce->session->set( 'chosen_shipping_methods', $chosen_shipping_methods);
}


add_action('woocommerce_checkout_update_order_review', 'mwa_checkfor_free_shipping', 9999 );
function mwa_checkfor_free_shipping($post){
    global $woocommerce;
    $chosen_shipping_methods = $woocommerce->session->get( 'chosen_shipping_methods');
    $chosen_shipping_methods[0] = "free_shipping";
    $woocommerce->session->set( 'chosen_shipping_methods', $chosen_shipping_methods);
    $chosen_shipping_methods = $woocommerce->session->get( 'chosen_shipping_methods');      
}