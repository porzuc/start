<?php
/**
 * Profil zawodnika.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$number    = get_post_meta( get_the_ID(), '_sn_number', true );
	$positions = sn_positions();
	$pos_key   = get_post_meta( get_the_ID(), '_sn_position', true );
	?>
	<section class="page-hero">
		<div class="container">
			<h1><?php echo '' !== $number ? esc_html( '#' . $number . ' ' ) : ''; the_title(); ?></h1>
			<div class="meta"><?php echo esc_html( ( 'brak' !== $pos_key && isset( $positions[ $pos_key ] ) ) ? $positions[ $pos_key ] : '' ); ?></div>
		</div>
	</section>
	<section class="content">
		<div class="container">
			<article class="entry">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'sn-player' ); ?>
				<?php endif; ?>
				<?php the_content(); ?>
			</article>
		</div>
	</section>
	<?php
endwhile;
get_footer();
