<?php
/**
 * Terminarz i wyniki.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$upcoming = new WP_Query( array(
	'post_type'      => 'mecz',
	'posts_per_page' => 50,
	'meta_key'       => '_sn_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'relation' => 'OR',
			array( 'key' => '_sn_goals_for', 'compare' => 'NOT EXISTS' ),
			array( 'key' => '_sn_goals_for', 'value' => '', 'compare' => '=' ),
		),
	),
) );
$played = new WP_Query( array(
	'post_type'      => 'mecz',
	'posts_per_page' => 50,
	'meta_key'       => '_sn_date',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
	'meta_query'     => array(
		array( 'key' => '_sn_goals_for', 'value' => '', 'compare' => '!=' ),
	),
) );
?>
<section class="page-hero">
	<div class="container"><h1><?php esc_html_e( 'Terminarz i wyniki', 'start-namyslow' ); ?></h1></div>
</section>

<section class="content">
	<div class="container">
		<h2 class="position-group"><?php esc_html_e( 'Nadchodzące mecze', 'start-namyslow' ); ?></h2>
		<div class="match-list">
			<?php
			if ( $upcoming->have_posts() ) {
				while ( $upcoming->have_posts() ) {
					$upcoming->the_post();
					sn_render_match( get_the_ID() );
				}
				wp_reset_postdata();
			} else {
				echo '<p>' . esc_html__( 'Brak zaplanowanych meczów.', 'start-namyslow' ) . '</p>';
			}
			?>
		</div>

		<h2 class="position-group"><?php esc_html_e( 'Rozegrane mecze', 'start-namyslow' ); ?></h2>
		<div class="match-list">
			<?php
			if ( $played->have_posts() ) {
				while ( $played->have_posts() ) {
					$played->the_post();
					sn_render_match( get_the_ID() );
				}
				wp_reset_postdata();
			} else {
				echo '<p>' . esc_html__( 'Brak rozegranych meczów.', 'start-namyslow' ) . '</p>';
			}
			?>
		</div>
	</div>
</section>
<?php
get_footer();
