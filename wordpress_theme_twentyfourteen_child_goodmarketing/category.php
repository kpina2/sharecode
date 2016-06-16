<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="main-content category-content">
		<div id="content" class="site-content" role="main">
                    <div class='post-left-col'>

			<?php if ( have_posts() ) : ?>

<!--			<header class="archive-header">-->
<!--				<h1 class="archive-title"><?php // printf( __( 'Category Archives: %s', 'twentyfourteen' ), single_cat_title( '', false ) ); ?></h1>-->

				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
<!--			</header> .archive-header -->

			<?php //
                                        $count = 0;
					// Start the Loop.
					while ( have_posts() ) : the_post();
//                                        $count++;
//                                        if($count >1){continue;}
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content-category-page', get_post_format() );

					endwhile;
					// Previous/next page navigation.
					twentyfourteen_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
                        </div>
                        <div class='post-right-col'>
                            <div class="post-right-col-content">
                                <?php
                                    $category =  get_the_category( $id );
                                    $cat_name = $category[0]->cat_name;

                                    $parent_cat = $category[0]->category_parent;
                                    if( !empty($parent_cat)){
                                        $cat_id = $parent_cat;
                                        $cat_name =  get_the_category_by_ID( $parent_cat );
                                    }else{
                                        $cat_id = $category[0]->cat_ID;
                                    }

                                ?>
                                <div class='category-list'>
                                    <h4 class='cat-head'><?php echo $cat_name . " Categories"; ?></h4>
                                    <?php wp_list_categories('title_li=&orderby=id&show_count=0&use_desc_for_title=0&child_of=' .$cat_id); ?>
                                </div>
                                <div id="post-sidebar-wrapper">
                                    <?php include('sidebar-post.php') ?>
                                </div>
                                Subscribe to keep in touch with the latest insights!
                                <?php include("subscribe-div.php"); ?>
                            </div>
                        </div>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php

get_footer();
