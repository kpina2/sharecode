<?php
/**
Template Name: Resources - Lending Library
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
                                        <?php $terms = get_terms("lending_library_categories"); ?>
                                        <?php foreach ( $terms as $term ): ?>
                                            <div>
                                                <h2><span class='arrow-indicator'></span><?php echo $term->name; ?></h2>
                                                <?php
                                                $prefix = 'aspiranet-lending-library-book-';
                                                $args = array(
                                                    'post_type' => 'lending_library_book',
                                                    'orderby' => 'post_title',
                                                    'order' => 'ASC',
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'lending_library_categories',
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
                                                        <?php $books = $postquery->get_posts(); $count = 0;?>
                                                        <?php foreach($books as $book): ?>
                                                            <?php $count++; $stripe = ($count%2===0 ? "even" : "odd"); ?>
                                                            <?php $subtitle = rwmb_meta( $prefix . 'subtitle', array(), $book->ID ); ?>
                                                            <?php $author = rwmb_meta( $prefix . 'author', array(), $book->ID ); ?>
                                                            <?php $year = rwmb_meta( $prefix . 'year', array(), $book->ID ); ?>
                                                            <?php $publisher = rwmb_meta( $prefix . 'publisher', array(), $book->ID ); ?>
                                                            <?php $description = rwmb_meta( $prefix . 'description', array(), $book->ID ); ?>
                                                            <?php $link = rwmb_meta( $prefix . 'link', array(), $book->ID ); ?>

                                                            <li class='aspiranet-category-item aspiranet-book <?php echo $stripe; ?>'>
                                                                <?php echo $book->post_title; ?><?php echo (!empty($subtitle) ? ": $subtitle" : ""); ?>
                                                                <?php echo (!empty($author) ||  !empty($publisher) || !empty($year) ? "<div class='aspiranet-category-item-details aspiranet-book-details'>" : ""); ?>
                                                                    <?php echo (!empty($author) ? "by $author" : ""); ?>
                                                                    <?php echo (!empty($publisher) ? ", $publisher" : ""); ?>
                                                                    <?php echo (!empty($year) ? ", $year" : ""); ?>
                                                                <?php echo (!empty($author) ||  !empty($publisher) || !empty($year) ? "</div>" : ""); ?>
                                                                <?php echo (!empty($description) ? "<p>$description</p>" : ""); ?>
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