<?php
/**
 * Pojedynczy mecz (relacja).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="container"><h1><?php esc_html_e( 'Mecz', 'start-namyslow' ); ?></h1></div>
	</section>
	<section class="content">
		<div class="container">
			<div class="entry">
				<div class="match-list"><?php sn_render_match( get_the_ID() ); ?></div>
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
