<?php 
	get_header();
?>

<div class="container">
	<div class="page-content page-404">
		<div class="row-fluid">
			<div class="span12">
				<h1 class="largest">404</h1>
				<h1><?php _e('Oops! Page not found', ETHEME_DOMAIN) ?></h1>
				<p><?php _e('Sorry, but the page you are looking for cannot be found. Please use the search form below to find what you\'re looking for.', ETHEME_DOMAIN) ?> </p>
				<div class="search error_page">
						<?php echo etheme_search(array()); ?>
				</div>
			</div>
		</div>


	</div>
</div>

	
<?php
	get_footer();
?>