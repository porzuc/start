<?php
/**
 * Podstrona sklepu (strona o adresie /sklep/).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="container"><h1><?php the_title(); ?></h1></div>
	</section>
	<section class="content">
		<div class="container">
			<div class="shop-banner shop-banner--static">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/shop-banner.jpg' ); ?>" alt="<?php esc_attr_e( 'Koszulka NKS Start Namysłów', 'start-namyslow' ); ?>">
			</div>
			<article <?php post_class( 'entry' ); ?>>
				<?php the_content(); ?>
			</article>
		</div>
	</section>
	<?php
endwhile;
get_footer();
