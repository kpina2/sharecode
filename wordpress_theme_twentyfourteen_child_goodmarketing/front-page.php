<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
                    <div id="gm-slider-wrapper">
<!--                        <span id="slider-prev"></span>-->
                        <ul class="bxslider">
                            <a href="<?php echo get_site_url(); ?>/category/insights/marketing-strategy/"><li>
                                <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/slider-img-marketing-strategy.png" />
                                Marketing Strategy
                            </li></a>
                            <a href="<?php echo get_site_url(); ?>/category/insights/guerilla-marketing/"><li>
                                <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/slider-img-guerilla-marketing.png" />
                                Guerilla Marketing
                            </li></a>
                            <a href="<?php echo get_site_url(); ?>/category/insights/fundraising/"><li>
                                <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/slider-img-fundraising.png" />
                                Fundraising
                            </li></a>
                        </ul>
<!--                        <span id="slider-next"></span>-->
                        <div class="clear-div"></div>
                    </div>
                    <div id="front-page-content-wrapper">
                    <?php
                        if ( have_posts() ) :
                                // Start the Loop.
                            while ( have_posts() ) : the_post();

                                    /*
                                    * Include the post format-specific template for the content. If you want to
                                    * use this in a child theme, then include a file called called content-___.php
                                    * (where ___ is the post format) and that will be used instead.
                                    */
                                    get_template_part( 'content-for-home', get_post_format() );

                            endwhile;
                            // Previous/next post navigation.
//                            twentyfourteen_paging_nav();

                        else :
                                // If no content, include the "No posts found" template.
                                get_template_part( 'content', 'none' );

                        endif;
                    ?>
                    </div>
                    <div class="clear-div"></div>
                    <div class='front-page-sidebar'>
                        <h1>Get Engaged + Stay in Touch!</h1>
                        <?php include('sidebar-front-page.php'); ?>
                    </div>
		</div><!-- #content -->
                <div class='homepage-recent-posts'>
                    <?php
                        $myposts = new WP_Query(array(
                            'numberposts' => '4'
                        ));
                        $count=0;
                    ?>
                    <?php if ( $myposts->have_posts() ) : ?>
                        <?php while ($myposts->have_posts()) : $myposts->the_post(); ?>
                            <?php $count ++; ?>
                            <?php get_template_part( 'content-front-page', 'myposts' ); ?>
                            <?php if($count == 2): ?>
                                <div class="clear-div"></div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    
                </div>
	</div><!-- #primary -->
	<?php // get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php get_footer(); ?>