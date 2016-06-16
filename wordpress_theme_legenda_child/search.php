<?php 
	get_header();
?>
<?php 
	extract(etheme_get_blog_sidebar());
	$postspage_id = get_option('page_for_posts');
        $team_search = false;
        if(!empty($_REQUEST['s']) && $_REQUEST['post_type'] == 'mwa_team'){
            $team_search = true;
            $content_span = 12;
        }
?>

<div class="page-heading bc-type-<?php etheme_option('breadcrumb_type'); ?>">
	<div class="container">
		<div class="row-fluid">
			<div class="span12 a-center">
                            <?php if($team_search): ?>
                                <h1 class="title"><span>TEAM STORES</span></h1>
                            <?php else: ?>
                                <h1 class="title"><span><?php echo get_search_query(); ?></span></h1>
                            <?php endif; ?>
                            <span><?php etheme_breadcrumbs(); ?></span>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="page-content sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">
		<div class="row">
                        <?php if(!$team_search): ?>
                            <?php if($position == 'left'): ?>
                                    <div class="<?php echo $sidebar_span; ?> sidebar sidebar-left">
                                            <?php etheme_get_sidebar($sidebarname); ?>
                                    </div>
                            <?php endif; ?>
                        <?php endif; ?>
			<div class="content <?php echo $content_span; ?>">
                            <?php if($team_search): ?>
                                <div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner ">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_single_image wpb_content_element vc_align_center   visible-desktop visible-tablet">
                                            <figure class="wpb_wrapper vc_figure">
                                                <a href="/team" target="_self" class="vc_single_image-wrapper   vc_box_border_grey"><img width="1200" height="473" src="https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores.jpg" class="vc_single_image-img attachment-full" alt="team-stores" srcset="https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-300x100.jpg 300w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-768x256.jpg 768w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-1024x341.jpg 1024w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores.jpg 1200w" sizes="(max-width: 1200px) 100vw, 1200px"></a>
                                            </figure>
                                        </div>
                                        <div class="wpb_single_image wpb_content_element vc_align_center   visible-phone">
                                            
                                            <figure class="wpb_wrapper vc_figure">
                                                <a href="/team" target="_self" class="vc_single_image-wrapper   vc_box_border_grey"><img width="480" height="480" src="https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores.jpg" class="vc_single_image-img attachment-full" alt="team-stores-m" srcset="https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-150x150.jpg 150w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-300x300.jpg 300w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-180x180.jpg 180w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores.jpg 480w" sizes="(max-width: 480px) 100vw, 480px"></a>
                                            </figure>
                                        </div>
                                    </div>
                                </div></div></div>
                            <?php endif; ?>
					<?php if ($blog_layout == 'grid'): ?>
						<div class="blog-masonry row">
					<?php endif ?>

						<?php if(have_posts()): while(have_posts()) : the_post(); ?>
                                                        <?php if(!$team_search): ?>
                                                            <?php get_template_part('content', $blog_layout); ?>
                                                        <?php else: ?>
                                                            <?php get_template_part('content', "mwa_team-grid"); ?>
                                                        <?php endif; ?>
						<?php endwhile; ?>

					<?php if ($blog_layout == 'grid'): ?>
						</div>
					<?php endif ?>

				<?php else: ?>

					<h1><?php _e('No posts were found!<br><span style="font-size:14px;">If you are looking for a team or team product, please <a href="/team/">click here</a> to search</span>', ETHEME_DOMAIN) ?></h1>

				<?php endif; ?>

				<div class="articles-nav">
					<div class="left"><?php next_posts_link(__('&larr; Older Posts', ETHEME_DOMAIN)); ?></div>
					<div class="right"><?php previous_posts_link(__('Newer Posts &rarr;', ETHEME_DOMAIN)); ?></div>
					<div class="clear"></div>
				</div>

			</div>
                        <?php if(!$team_search): ?>
                            <?php if($position == 'right'): ?>
                                    <div class="<?php echo $sidebar_span; ?> sidebar sidebar-right">
                                            <?php etheme_get_sidebar($sidebarname); ?>
                                    </div>
                            <?php endif; ?>
                        <?php endif; ?>
		</div>


	</div>
</div>

	
<?php
	get_footer();
?>