<?php
/**
 * Karta zawodnika (styl: zdjęcie, numer, imię, nazwisko, pozycja).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$number    = get_post_meta( get_the_ID(), '_sn_number', true );
$pos_key   = get_post_meta( get_the_ID(), '_sn_position', true );
$positions = sn_positions();
$pos_label = ( 'brak' !== $pos_key && isset( $positions[ $pos_key ] ) ) ? $positions[ $pos_key ] : '';
$parts     = preg_split( '/\s+/u', trim( get_the_title() ) );
$last      = count( $parts ) > 1 ? array_pop( $parts ) : '';
$first     = implode( ' ', $parts );
if ( '' === $last ) {
	$last  = $first;
	$first = '';
}
?>
<article class="player">
	<a class="player__photo" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sn-player' ); ?>
		<?php else : ?>
			<span class="placeholder"></span>
		<?php endif; ?>
		<?php if ( '' !== $number ) : ?>
			<span class="player__number"><?php echo esc_html( $number ); ?></span>
		<?php endif; ?>
	</a>
	<div class="player__info">
		<?php if ( '' !== $first ) : ?>
			<span class="player__first"><?php echo esc_html( $first ); ?></span>
		<?php endif; ?>
		<h3 class="player__name"><a href="<?php the_permalink(); ?>"><?php echo esc_html( $last ); ?></a></h3>
		<span class="player__pos"><?php echo esc_html( $pos_label ); ?></span>
	</div>
</article>
