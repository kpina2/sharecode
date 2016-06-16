<?php
/**
Template Name: Resources - Newsletters
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
                                    
					<?php the_content(); ?>
                                            
                                        <?php
                                            $prefix = 'aspiranet-newsletter-resource-';
                                            $args = array(
                                                'post_type' => 'newsletter_resource',
                                                'orderby' => 'post_title',
                                                'order' => 'ASC',
                                                "nopaging"=>1
                                            );
                                            $postquery = new WP_Query($args);
                                            $posts = $postquery->get_posts();
                                        
                                        $post_display_array = array();
                                        $most_recent = 0;
                                        foreach($posts as $post){
                                            
                                            $file = rwmb_meta( $prefix . 'file', array(), $post->ID );
                                               
                                            $year_string = rwmb_meta( $prefix . 'year', array(), $post->ID );
                                            $quarter_string = rwmb_meta( $prefix . 'quarter', array(), $post->ID );
                                            
                                            $most_recent_test = (int) $year_string . $quarter_string;
                                            
                                            if($most_recent_test > $most_recent){
                                                $most_recent = $most_recent_test;
                                                $most_recent_post = array();
                                                $most_recent_post['post'] = $post;
                                                $most_recent_post['file'] = $file;
                                            }
                                            
                                            $year = (int) $year_string;
                                            $quarter = (int) $quarter_string;
                                            
                                            $post_display_array[$year][$quarter]['file'] = $file;
                                            $post_display_array[$year][$quarter]['post'] = $post;
                                        }
                                        krsort($post_display_array);
                                        ?>
                                        
                                        <?php $first=true; ?>
                                        
                                        <?php if(!empty($most_recent_post)): ?>
                                            <h2>Current Newsletter</h2>
                                            <p style="margin-left: 20px; font-weight: bold;">
                                                
                                                <?php 
                                                if(!empty($most_recent_post['file'])){
                                                    $url = wp_get_attachment_url( $most_recent_post['file'] );
                                                }
                                                ?>
                                                <?php echo (!empty($url) ? "<a href='" . $url . "'>" : ""); ?>
                                                    <?php echo $most_recent_post['post']->post_title; ?>
                                                <?php echo (!empty($url) ? "</a>" : ""); ?>
                                            </p>
                                        <?php endif; ?>
                                        <h2>Previous Newsletters</h2>
                                    <div class="aspiranet-list aspiranet-newsletters" id='aspiranet-expandable-list'>
                                        <?php foreach($post_display_array as $year => $quarters): ?>
                                            <div>
                                                <h2><span class='arrow-indicator'></span><?php echo $year; ?></h2>
                                                <ul class='aspiranet-category'>
                                                    <?php krsort($quarters); ?>
                                                    <?php foreach($quarters as $quarter => $post): ?>
                                                        <?php if($first==true){$first=false;continue;} ?>
                                                        <li>
                                                            <?php 
                                                            if(!empty( $post['file'])){
                                                                $url = wp_get_attachment_url( $post['file'] );
                                                            }else{
                                                                $url = "";
                                                            }
                                                            ?>
                                                            <?php echo (!empty($url) ? "<a class='newsletter-resource-link' href='" . $url . "'>" : ""); ?>
                                                                <?php echo $post['post']->post_title; ?>
                                                            <?php echo (!empty($url) ? "</a>" : ""); ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
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