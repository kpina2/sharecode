<?php if ( is_active_sidebar( 'sidebar-post' ) ) : ?>
    <div id="post-sidebar-widgets" class="post-sidebar-widgets widget-area" role="">
            <?php dynamic_sidebar( 'sidebar-post' ); ?>
    </div><!-- #primary-sidebar -->
<?php endif; ?>