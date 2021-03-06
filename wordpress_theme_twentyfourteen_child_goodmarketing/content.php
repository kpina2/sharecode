<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<div class='post-left-col'>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php twentyfourteen_post_thumbnail(); ?>

            <header class="entry-header">
                    <?php
                            if ( is_single() ) :
                                    the_title( '<h1 class="entry-title">', '</h1>' );
                            else :
                                    the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
                            endif;
                    ?>
                    <?php $author = get_the_author(); ?> 
                    <div class="entry-meta">
                    <span>By <a href='<?php echo get_site_url(); ?>/author/<?php echo the_author_meta('nickname'); ?>'><?php echo $author; ?></a></span> &bull;
                        <?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
                        <?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && twentyfourteen_categorized_blog() ) : ?>
                            <span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) ); ?></span> &bull;
                        <?php endif; ?>
                        <span>Comments <?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '(1)', 'twentyfourteen' ), __( '(%)', 'twentyfourteen' ) ); ?>
                        </span>
                        <?php
                            endif;
                            edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
                        ?>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <?php if ( is_search() ) : ?>
            <div class="entry-summary">
                    <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
            <?php else : ?>
            <div class="entry-content">
                    <?php
                            the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ) );
                            wp_link_pages( array(
                                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                            ) );
                    ?>
            </div><!-- .entry-content -->
            <?php endif; ?>

            <?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
            <?php twentyfourteen_post_nav(true); ?>
    </article><!-- #post-## -->
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
<div class='clear-div'></div>