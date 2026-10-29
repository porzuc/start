<?php
/**
 * Karta aktualności.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'card' ); ?>>
	<a class="card__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sn-card' ); ?>
		<?php else : ?>
			<?php $sn_fb = ( isset( $args['fallback'] ) ? (int) $args['fallback'] : get_the_ID() ) % 5 + 1; ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/news/news-' . $sn_fb . '.jpg' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
		<?php endif; ?>
	</a>
	<div class="card__body">
		<div class="card__date"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></div>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
		<a class="card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Czytaj dalej →', 'start-namyslow' ); ?></a>
	</div>
</article>
