<?php
/**
 * Strona 404.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="page-hero">
	<div class="container"><h1>404</h1></div>
</section>
<section class="content">
	<div class="container" style="text-align:center">
		<p><?php esc_html_e( 'Nie znaleziono strony, której szukasz.', 'start-namyslow' ); ?></p>
		<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Wróć na stronę główną', 'start-namyslow' ); ?></a>
	</div>
</section>
<?php
get_footer();
