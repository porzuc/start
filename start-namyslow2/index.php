<?php
/**
 * Lista wpisów (aktualności) – szablon domyślny.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="page-hero">
	<div class="container">
		<h1>
			<?php
			if ( is_search() ) {
				printf( esc_html__( 'Wyniki wyszukiwania: %s', 'start-namyslow' ), esc_html( get_search_query() ) );
			} elseif ( is_archive() ) {
				the_archive_title();
			} else {
				esc_html_e( 'Aktualności', 'start-namyslow' );
			}
			?>
		</h1>
	</div>
</section>

<section class="content">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '←',
				'next_text' => '→',
			) );
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nic tu jeszcze nie ma.', 'start-namyslow' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
