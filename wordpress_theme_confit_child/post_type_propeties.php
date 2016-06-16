<?php
/**
Template Name: Properties For Sale List
*/

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template( '', true );
					?>

				<?php endwhile; // end of the loop. ?>
                                <?php
                                    $args = array(
                                        'post_type' => 'property',
                                        'orderby' => 'post_date',
                                        'order' => 'DESC',
                                        "nopaging"=>1, 
                                        'post_per_page' => -1
                                    );
                                    $postquery = new WP_Query($args);
                                ?>
                                <?php if($postquery->have_posts()): ?>
                                    <article>
                                        <header class='entry-header'>
                                            <h1 class='entry-title'>Property List</h1>
                                        </header>
                                        <?php $properties = $postquery->get_posts(); $count = 0;?>
                                        <?php foreach($properties as $property): ?>
                                            <?php $count ++; ?>
                                            <?php if($count > 1): ?>
                                                <hr>
                                            <?php endif; ?>
                                            <div class='entry-content' style='margin-bottom: 20px;'>
                                                <?php $date_posted = get_the_date( "m-d-Y", $property->ID ); ?>
                                                <?php $phase = rwmb_meta( 'sheepcreek-property-phase', array(), $property->ID ); ?>
                                                <?php $lot_size = rwmb_meta( 'sheepcreek-property-lot_size', array(), $property->ID ); ?>
                                                <?php $owner = rwmb_meta( 'sheepcreek-property-owner', array(), $property->ID ); ?>
                                                <?php $agent = rwmb_meta( 'sheepcreek-property-agent', array(), $property->ID ); ?>
                                                <?php $agent_phone = rwmb_meta( 'sheepcreek-property-agent_phone', array(), $property->ID ); ?>
                                                <?php $agent_email = rwmb_meta( 'sheepcreek-property-agent_email', array(), $property->ID ); ?>
                                                <?php $owner_phone = rwmb_meta( 'sheepcreek-property-owner_phone', array(), $property->ID ); ?>
                                                <?php $owner_email = rwmb_meta( 'sheepcreek-property-owner_email', array(), $property->ID ); ?>
                                                <?php $description = rwmb_meta( 'sheepcreek-property-description', array(), $property->ID ); ?>

                                                 <?php $terms = get_the_terms($property->ID, 'property_categories' ); ?>
                                                
                                                <h2 style='font-size: 1.5em; font-weight: bold; text-align: center; margin: 0; text-decoration: underline;'><?php echo $property->post_title; ?></h2>
                                                <h5 style='font-size: 0.8em; font-style: italic; text-align: center; margin: 0;'><?php echo $date_posted; ?></h5>
                                                <div style='width: 70%; float: left;'>
                                                    <?php if($terms): ?>
                                                        <?php foreach($terms as $term): ?>
                                                            <span style='color: blue; font-weight: bold;'><?php echo $term->name; ?> </span>
                                                        <?php endforeach; ?>
                                                        <br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($lot_size)): ?>
                                                        <strong>Owner:</strong> <?php echo $owner; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($owner_phone)): ?>
                                                        <strong>Owner Phone:</strong> <?php echo $owner_phone; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($owner_email)): ?>
                                                        <strong>Owner Email:</strong> <?php echo $owner_email; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($agent)): ?>
                                                        <strong>Agent:</strong> <?php echo $agent; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($agent)): ?>
                                                        <strong>Agent:</strong> <?php echo $agent; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($agent_phone)): ?>
                                                        <strong>Agent Phone:</strong> <?php echo $agent_phone; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($agent_email)): ?>
                                                        <strong>Agent Email:</strong> <?php echo $agent_email; ?><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($description)): ?>
                                                        <strong>Description:</strong> <?php echo $description; ?><br>
                                                    <?php endif; ?>
                                                </div>
                                                <div style='width: 30%; float: left;'>
                                                    <?php if(!empty($phase)): ?>
                                                        <a href="http://sheepcreek.kaypiem.com/wp-content/uploads/2013/03/Development.jpg" rel="lightbox[dev_maps_<?php echo $property->ID; ?>]">Development Map</a>
                                                        <br>
                                                        <a href="http://sheepcreek.kaypiem.com/wp-content/uploads/2013/03/<?php echo str_replace(" ", "", $phase); ?>.jpg" rel="lightbox[dev_maps_<?php echo $property->ID; ?>]"><?php echo $phase; ?> Map</a>
                                                    <?php endif; ?>
                                                </div>
                                                <div style='clear:both;'></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </article>
                                <?php endif; ?>
			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>