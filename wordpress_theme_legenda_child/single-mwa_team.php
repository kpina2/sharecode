<?php 
    $mwa_team_user = get_team_user();
    get_header('shop');
    extract(etheme_get_shop_sidebar());
    $sidebarname = 'shop';
?>
<div class="page-heading bc-type-<?php etheme_option('breadcrumb_type'); ?>">
	<div class="container">
		<div class="row-fluid">
			<div class="span12 a-center">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                            <h1 class="title"><span><?php the_title(); ?></span></h1>
                                <?php endif; ?>
				<?php
					/**
					 * woocommerce_before_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					 */
					do_action('woocommerce_before_main_content');
				?>
			</div>
		</div>
	</div>
</div>

<div class="container">
    <?php if($mwa_team_user): ?>
	<div class="page-content sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">
		
		<div class="row-fluid">
                    
			<?php if($position == 'left'): ?>
				<div class="<?php echo $sidebar_span; ?> sidebar sidebar-left">
                                    <?php dynamic_sidebar( 'Team Shop Sidebar' ); ?>
				</div>
			<?php endif; ?>
			
			<div class="content <?php echo $content_span; ?>">
                                        <?php
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
                                        $product_loop = new WP_Query( $args );
                                        
                                        ?>
					<?php if ( $product_loop->have_posts() ) : ?>
                                       
                                            <?php do_action( 'woocommerce_archive_description' ); ?>
						
                                            <?php if (woocommerce_products_will_display()): ?>

                                                    <div class="toolbar toolbar-top">
                                                            <?php
                                                                    /**
                                                                     * woocommerce_before_shop_loop hook
                                                                     *
                                                                     * @hooked woocommerce_result_count - 20
                                                                     * @hooked woocommerce_catalog_ordering - 30
                                                                     */
                                                                    do_action( 'woocommerce_before_shop_loop' );
                                                            ?>
                                                            <div class="clear"></div>
                                                    </div>

                                            <?php endif ?>

                                            <?php $cats_displayed = woocommerce_product_subcategories(array('before' => '<div class="loop-subcategories">', 'after' => '<div class="clear"></div></div>')); ?>

                                                <?php woocommerce_product_loop_start(); ?>

							<?php while ( $product_loop->have_posts() ) : $product_loop->the_post(); ?>
                                                                <?php
                                                                    global $product;
                                                                    if(isset($_GET['pa_brand'])){
                                                                        $brands_list = explode(", ", $product->get_attribute( 'pa_brand' ));
                                                                        if(!in_array(urldecode($_GET['pa_brand']), $brands_list)){
                                                                            continue;
                                                                        }
                                                                    }
                                                                    if(isset($_GET['pa_color'])){
                                                                        $colors_list = explode(", ", $product->get_attribute( 'pa_color' ));
                                                                        if(!in_array(urldecode($_GET['pa_color']), $colors_list)){
                                                                            continue;
                                                                        }
                                                                    }
              if(isset($_GET['pa_nca-categories'])){
                  $agegroup = $product->get_attribute( 'pa_nca-categories' );
                  $match = preg_match('/' . urldecode($_GET['pa_nca-categories']) . '/', $agegroup);
                  if(!$match){
                    continue;
                  }
              }
                                                                    
                                                                    if(isset($_GET['category_filter'])){
//                                                                        var_dump($product);
                                                                        $terms = wp_get_post_terms($product->id,'product_cat');
                                                                        $found=false;
                                                                        foreach($terms as $term){
                                                                            if($term->name ==  urldecode($_GET['category_filter'])){
                                                                                $found=true;
                                                                            }
                                                                        }
                                                                        if(!$found){
                                                                            continue;
                                                                        }
                                                                    }
                                                                ?>
								<?php woocommerce_get_template_part( 'content', 'product' ); ?>
			
							<?php endwhile; // end of the loop. ?>

							<?php if (etheme_get_option('product_img_hover') == 'tooltip'): ?>
								<script type="text/javascript">imageTooltip(jQuery('.imageTooltip'));</script>
							<?php endif ?>
							
							<div class="clear"></div>
							
						<?php woocommerce_product_loop_end(); ?>
                                               
						<div class="toolbar toolbar-bottom">
							<?php
								/**
								 * woocommerce_after_shop_loop hook
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								do_action( 'woocommerce_after_shop_loop' );
							?>
							<div class="clear"></div>
						</div>
			
					<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
			
						<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
			
					<?php endif; ?>
                                         <?php wp_reset_postdata(); ?>              
				<?php
					/**
					 * woocommerce_after_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action('woocommerce_after_main_content');
				?>
			</div>

			<?php if($position == 'right'): ?>
				<div class="<?php echo $sidebar_span; ?> sidebar sidebar-right">
					<?php etheme_get_sidebar($sidebarname); ?>
				</div>
			<?php endif; ?>
		</div>
            <form action="" method="post" style="float:right; margin:25px;">
                <input name='mwa_logout' type="hidden" value="true">
                <input type="submit" value="Team Logout">
            </form>
	</div>
        <?php else: ?>
            <div class="search teams"><div class="et-mega-search">
	            <form action="" method="post">
	                <label>Enter Team Password: </label>
	                <input name='mwa_password' type="password">
	                <input type="submit" class="margin" value="Submit">
	            </form>
	        </div></div>
        <?php endif; ?>
</div>
<?php
    get_footer();
?>