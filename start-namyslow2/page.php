<?php
/**
 * Zwykła strona (np. Kontakt, O klubie).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="content">
		<div class="container">
			<article <?php post_class( 'entry' ); ?>>
				<?php the_content(); ?>
			</article>
		</div>
	</section>
	<?php
endwhile;
get_footer();
