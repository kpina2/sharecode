<?php
/**
Template Name: Resources - Web
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
                                    <div class="aspiranet-list aspiranet-websites" id='aspiranet-expandable-list'>
					<?php the_content(); ?>
                                        <?php $terms = get_terms("web_resource_categories"); ?>
                                        <?php foreach ( $terms as $term ): ?>
                                            <div>
                                                <h2><span class='arrow-indicator'></span><?php echo $term->name; ?></h2>
                                                <?php
                                                $prefix = 'aspiranet-website-resource-';
                                                $args = array(
                                                    'post_type' => 'website_resource',
                                                    'orderby' => 'post_title',
                                                    'order' => 'ASC',
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'web_resource_categories',
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
                                                        <?php $websites = $postquery->get_posts(); $count = 0;?>
                                                        <?php foreach($websites as $website): ?>
                                                            <?php $count++; $stripe = ($count%2===0 ? "even" : "odd"); ?>
                                                            <?php $url = rwmb_meta( $prefix . 'url', array(), $website->ID ); ?>
                                                            <?php $notes = rwmb_meta( $prefix . 'notes', array(), $website->ID ); ?>

                                                            <li class='aspiranet-category-item aspiranet-website <?php echo $stripe; ?>'>
                                                                <?php echo (!empty($url) ? "<a class='web-resource-link' target='_blank' href='" . $url . "'>" : ""); ?>
                                                                    <?php echo $website->post_title; ?>
                                                                <?php echo (!empty($url) ? "</a>" : ""); ?>
                                                                <?php echo (!empty($notes) ? "<div>" . $notes . "</div>": ""); ?>
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