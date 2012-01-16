<?php get_header(); ?>

<?php if ( have_posts() ): ?>

	<?php
	while( have_posts() ):
		the_post();
		get_template_part( 'content', timepiece_get_format() );
	endwhile;	
	?>

<?php endif; ?>

<?php get_footer(); ?>