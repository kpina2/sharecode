<?php
/**
Template Name: Resources - Articles
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
                                    <div class="aspiranet-list aspiranet-books" id='aspiranet-expandable-list'>
					<?php the_content(); ?>
                                        <?php $terms = get_terms("article_resource_categories"); ?>
                                        <?php foreach ( $terms as $term ): ?>
                                            <div>
                                                <h2><span class='arrow-indicator'></span><?php echo $term->name; ?></h2>
                                                <?php
                                                $prefix = 'aspiranet-article-resource-';
                                                $args = array(
                                                    'post_type' => 'article_resource',
                                                    'orderby' => 'post_title',
                                                    'order' => 'ASC',
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'article_resource_categories',
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
                                                        <?php $articles = $postquery->get_posts(); $count = 0;?>
                                                        <?php foreach($articles as $article): ?>
                                                            <?php $count++; $stripe = ($count%2===0 ? "even" : "odd"); ?>
                                                            <?php $file = rwmb_meta( $prefix . 'file', array(), $article->ID ); ?>
                                                            <?php $webpage = rwmb_meta( $prefix . 'web-page', array(), $article->ID ); ?>
                                                            <?php // var_dump($file); ?>
                                                            <?php if(!empty($file)){
                                                                    $url = wp_get_attachment_url( $file );
                                                                    $path_parts = pathinfo($url);
                                                                    $filetype = $path_parts['extension'];
                                                                    
                                                                    switch($filetype){
                                                                        case "pdf":
                                                                            $iconclass = "fa-file-pdf-o";
                                                                            break;
                                                                        case "doc":
                                                                            $iconclass = "fa-file-word-o";
                                                                            break;
                                                                        case "docx":
                                                                            $iconclass = "fa-file-word-o";
                                                                            break;
                                                                        case "xls":
                                                                            $iconclass = "fa-file-excel-o";
                                                                            break;
                                                                        case "xlsx":
                                                                            $iconclass = "fa-file-excel-o";
                                                                            break;
                                                                        default:
                                                                            $iconclass = "fa-file";
                                                                    }
                                                                    
                                                                    
                                                                }elseif(!empty($webpage)){
                                                                    $url = $webpage;
                                                                    $iconclass = "fa-external-link";
                                                                }else{
                                                                    $url =null;
                                                                    $iconclass = null;
                                                                }
                                                            ?>
                                                            <li class='aspiranet-category-item aspiranet-article <?php echo $stripe; ?>'>
                                                                <?php if(!empty($iconclass)): ?>
                                                                    <i class="fa <?php echo $iconclass; ?> fa-lg"></i>
                                                                <?php endif; ?>
                                                                <?php echo (!empty($url) ? "<a class='article-resource-link' target='_blank' href='" . $url . "'>" : ""); ?>
                                                                    <?php echo $article->post_title; ?>
                                                                <?php echo (!empty($url) ? "</a>" : ""); ?>
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