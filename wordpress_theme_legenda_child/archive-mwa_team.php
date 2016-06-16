<?php 
	get_header();
?>
<!--
<div class="page-heading bc-type-<?php // etheme_option('breadcrumb_type'); ?>">
	<div class="container">
		<div class="row-fluid">
			<div class="span12 a-center">
				<h1 class="title"><span>TEAM STORES</span></h1>
				<?php // etheme_breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>-->

<div class="container">
	<div class="page-content sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">
		<div class="row">
			<div class="content <?php echo $content_span; ?>">
                <div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="wpb_single_image wpb_content_element vc_align_center visible-desktop visible-tablet">
							<figure class="wpb_wrapper vc_figure">
								<a href="/team" target="_self" class="vc_single_image-wrapper   vc_box_border_grey"><img width="1200" height="473" src="https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores.jpg" class="vc_single_image-img attachment-full" alt="team-stores" srcset="https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-300x100.jpg 300w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-768x256.jpg 768w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores-1024x341.jpg 1024w, https://www.makingwavesusa.com/wp-content/uploads/2016/01/team-stores.jpg 1200w" sizes="(max-width: 1200px) 100vw, 1200px"></a>
							</figure>
						</div>
						<div class="wpb_single_image wpb_content_element vc_align_center visible-phone">
							
							<figure class="wpb_wrapper vc_figure">
								<a href="/team" target="_self" class="vc_single_image-wrapper   vc_box_border_grey"><img width="480" height="480" src="https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores.jpg" class="vc_single_image-img attachment-full" alt="team-stores-m" srcset="https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-150x150.jpg 150w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-300x300.jpg 300w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores-180x180.jpg 180w, https://www.makingwavesusa.com/wp-content/uploads/2016/02/MobileView_Banners-TeamStores.jpg 480w" sizes="(max-width: 480px) 100vw, 480px"></a>
							</figure>
						</div>
					</div>
                </div></div></div>
                <h3 class="team_h3">Use the search bar below to search for your&nbsp;team&nbsp;name</h3>
               <div class="search teams"><div class="et-mega-search">
                   <form method="get" id="searchform" class="hide-input" action="">
                        <input type="text" name="s" id="s" placeholder="<?php esc_attr_e( 'Search...', ETHEME_DOMAIN ); ?>" />
                        <input type="hidden" name="post_type" value="mwa_team" />
                        <input type="submit" value="<?php esc_attr_e( 'Go', ETHEME_DOMAIN ); ?>" class="button" />
                        <div class="clear"></div>
                    </form>
                </div></div>
			</div>

		</div>


	</div>
</div>

	
<?php
	get_footer();
?>