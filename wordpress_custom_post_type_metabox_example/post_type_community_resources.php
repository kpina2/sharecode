<?php
/**
Template Name: Resources - Community
*/
get_header(); ?>
<?php if ( have_posts() ) : the_post(); ?>
<div class="C">
    <?php
            include("includes/page_header.inc.php");
            $thisPageID =  $post->ID;
    ?>

    <div class="contentCols">
        <div class="colLeft">
            <div class="navPanel">
                    <?php
                            include( "includes/wp_nav_menu.inc.php" );
                    ?>
            </div>
            <?php
                    include ("includes/subscriptionWidget.inc.php");
            ?>
        </div>
        <div class="colRight">
            <div class="bc cf">
                    <?php
                            include("includes/bc.inc.php");
                    ?>
                    <?php
                            include("includes/sharethis.inc.php");
                    ?>
            </div>

            <div class="contentFluid">
                <div class="contentWrapper">
                    <div class="aspiranet-list aspiranet-contacts" id='aspiranet-expandable-list'>
                        <?php the_content(); ?>
                        <?php $terms = get_terms("community_contact_categories"); ?>
                        <?php foreach ( $terms as $term ): ?>
                            <div>
                                <h2><span class='arrow-indicator'></span><?php echo $term->name; ?></h2>
                                <?php
                                $prefix = 'aspiranet-community-contact-';
                                $args = array(
                                    'post_type' => 'community_contact',
                                    'orderby' => 'post_title',
                                    'order' => 'ASC',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'community_contact_categories',
                                            'field' => 'id',
                                            'terms' => $term->term_id
                                        )
                                    ),
                                    "nopaging"=>1 
//                                                    'post_per_page' => -1
                                );
                                $postquery = new WP_Query($args);
                                ?>
                                <?php if($postquery->have_posts()): ?>
                                    <ul class='aspiranet-category'>
                                        <?php $contacts = $postquery->get_posts(); $count = 0;?>
                                        <?php foreach($contacts as $contact): ?>
                                            <?php $count++; $stripe = ($count%2===0 ? "even" : "odd"); ?>
                                            <?php $address = rwmb_meta( $prefix . 'address', array(), $contact->ID ); ?>
                                            <?php $phone = rwmb_meta( $prefix . 'phone', array(), $contact->ID ); ?>
                                            <?php $website = rwmb_meta( $prefix . 'website', array(), $contact->ID ); ?>

                                            <li class='aspiranet-category-item aspiranet-contact <?php echo $stripe; ?>'>
                                                <strong><?php echo $contact->post_title; ?></strong>
                                                <?php echo (!empty($address) ? wpautop($address) : ""); ?>
                                                <?php echo (!empty($phone) ? "$phone<br>" : ""); ?>
                                                <?php echo (!empty($website) ? "<a href='$year'>Website</a>" : ""); ?>
                                            </li>
                                        <?php endforeach; ?>
                                        <?php wp_reset_postdata(); ?>
                                    </ul>
                                <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="colAfterRight">&nbsp;</div>
    </div>
</div>
<?php
endif;
?>
<?php get_footer(); ?>
<script>
    $(document).ready(function(){
        $("#aspiranet-expandable-list div h2").click(function(){
          $(".aspiranet-category", $(this).parent()).stop();
          $(".aspiranet-category", $(this).parent()).slideToggle();
          $(this).stop();
          $(this).toggleClass("category-open");
        });
    });
</script>
<style>
   
</style>