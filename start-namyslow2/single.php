<?php
/**
 * Pojedynczy wpis.
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
			<div class="meta"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?> • <?php the_category( ', ' ); ?></div>
		</div>
	</section>

	<section class="content">
		<div class="container">
			<article <?php post_class( 'entry' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large' ); ?>
				<?php endif; ?>
				<?php the_content(); ?>

				<div class="post-nav">
					<span><?php previous_post_link( '← %link' ); ?></span>
					<span><?php next_post_link( '%link →' ); ?></span>
				</div>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		</div>
	</section>
	<?php
endwhile;
get_footer();
