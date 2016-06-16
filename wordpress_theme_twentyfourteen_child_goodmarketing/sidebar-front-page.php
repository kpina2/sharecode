<div id="frontpage-sidebar-widgets" class="frontpage-sidebar-widgets widget-area" role="">
    <?php if ( is_active_sidebar( 'sidebar-front-page' ) ) : ?>
            <?php dynamic_sidebar( 'sidebar-front-page' ); ?>
    <?php endif; ?>
</div>
<div id='front-sidebr-subscirbe'>
    <?php include("subscribe-div.php"); ?>
</div>
<div class="clear-div"></div>
