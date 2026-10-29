<?php
/**
 * Kadra – zawodnicy pogrupowani według pozycji.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="page-hero">
	<div class="container"><h1><?php esc_html_e( 'Kadra', 'start-namyslow' ); ?></h1></div>
</section>

<section class="content">
	<div class="container">
		<?php
		$any = false;
		foreach ( sn_positions() as $key => $label ) {
			$q = new WP_Query( array(
				'post_type'      => 'zawodnik',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'meta_query'     => array(
					array( 'key' => '_sn_position', 'value' => $key ),
				),
			) );
			if ( ! $q->have_posts() ) {
				continue;
			}
			$any = true;
			echo '<h2 class="position-group">' . esc_html( $label ) . '</h2>';
			echo '<div class="grid grid--4">';
			while ( $q->have_posts() ) {
				$q->the_post();
				get_template_part( 'template-parts/player' );
			}
			echo '</div>';
			wp_reset_postdata();
		}
		if ( ! $any ) {
			echo '<p>' . esc_html__( 'Brak zawodników. Dodaj ich w panelu: Kadra → Dodaj zawodnika.', 'start-namyslow' ) . '</p>';
		}
		?>
	</div>
</section>
<?php
get_footer();
