<?php if ( is_active_sidebar( 'sidebar-page' ) ) : ?>
    <div id="page-sidebar-widgets" class="page-sidebar-widgets widget-area" role="">
            <?php dynamic_sidebar( 'sidebar-page' ); ?>
    </div><!-- #primary-sidebar -->
<?php endif; ?>