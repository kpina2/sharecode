<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
                    </div><!-- #main-wrapper -->
		</div><!-- #main -->
            
		<footer id="colophon" class="site-footer" role="contentinfo">

			<?php get_sidebar( 'footer' ); ?>
                        <div id="footer-logo">
                            <img src="<?php echo get_theme_root_uri(); ?>/goodmarketing/images/Footer-Logo.png">
                        </div>
                            
			<div class="site-info">
				<?php // do_action( 'twentyfourteen_credits' ); ?>
<!--				<a href="<?php // echo esc_url( __( 'http://wordpress.org/', 'twentyfourteen' ) ); ?>"><?php // printf( __( 'Proudly powered by %s', 'twentyfourteen' ), 'WordPress' ); ?></a>-->
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
    <script src="<?php echo get_theme_root_uri(); ?>/goodmarketing/js/jquery.bxslider/jquery.bxslider.js"></script>
    <link href='<?php echo get_theme_root_uri(); ?>/goodmarketing/js/jquery.bxslider/jquery.bxslider.css' rel='stylesheet' type='text/css'>
        
    <script>
        jQuery(document).ready(function(){
            slider = jQuery('.bxslider').bxSlider({
//                nextSelector: '#slider-next',
//                prevSelector: '#slider-prev',
                nextText: '',
                prevText: '',
                slideWidth: "261",
                useCSS: false,
                pager: false,
                auto: true,
                pause: 4500,
            });
            
//            jQuery("#slider-prev").click(function(){
//                slider.goToPrevSlide();
//            });
//            jQuery("#slider-next").click(function(){
//                slider.goToNextSlide();
//            });
        });
    </script>
</body>
</html>