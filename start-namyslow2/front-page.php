<?php
/**
 * Strona główna.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$slides = sn_hero_slides();
?>

<section class="hero">
	<div class="hero__slider" data-interval="<?php echo esc_attr( max( 2, (int) get_theme_mod( 'sn_hero_interval', 5 ) ) * 1000 ); ?>" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Zdjęcia z meczów', 'start-namyslow' ); ?>">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<div class="hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( $slide['url'] ); ?>');background-position:<?php echo esc_attr( $slide['pos'] ); ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' / ' . count( $slides ) ); ?>"></div>
		<?php endforeach; ?>
		<div class="hero__shade"></div>
		<?php if ( count( $slides ) > 1 ) : ?>
			<div class="hero__controls">
				<div class="hero__dots">
					<?php foreach ( $slides as $i => $slide ) : ?>
						<button type="button" class="hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Zdjęcie %d', 'start-namyslow' ), $i + 1 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="container">
		<span class="hero__kicker"><?php echo esc_html( sn_opt( 'sn_hero_kicker', 'IV liga • sezon 2026/27' ) ); ?></span>
		<h1><?php echo esc_html( sn_opt( 'sn_hero_title', 'NKS Start Namysłów' ) ); ?></h1>
		<p><?php echo esc_html( sn_opt( 'sn_hero_text', '' ) ); ?></p>
		<div class="hero__actions">
			<a class="btn" href="<?php echo esc_url( get_post_type_archive_link( 'mecz' ) ); ?>"><?php esc_html_e( 'Terminarz i wyniki', 'start-namyslow' ); ?></a>
			<a class="btn btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'zawodnik' ) ); ?>"><?php esc_html_e( 'Poznaj kadrę', 'start-namyslow' ); ?></a>
		</div>
	</div>
</section>

<?php
$upcoming_ids = sn_upcoming_matches( 5 )->posts;
$next_id      = $upcoming_ids ? (int) array_shift( $upcoming_ids ) : 0;
$played_ids   = array_reverse( sn_played_matches( 4 )->posts ); // od najstarszego do najnowszego
$next_ts      = $next_id ? sn_match_data( $next_id )['ts'] : 0;
?>
<!-- Terminarz -->
<section class="section section--dark sch" id="terminarz">
	<div class="container">
		<div class="sch__head">
			<div>
				<span class="sch__eyebrow"><?php esc_html_e( 'Mecze pierwszej drużyny', 'start-namyslow' ); ?></span>
				<h2 class="sch__title"><?php esc_html_e( 'Terminarz', 'start-namyslow' ); ?></h2>
			</div>
			<?php if ( $next_ts ) : ?>
				<div class="sch-countdown" data-ts="<?php echo esc_attr( $next_ts ); ?>">
					<span class="sch-countdown__label"><?php esc_html_e( 'Najbliższy mecz za:', 'start-namyslow' ); ?></span>
					<div class="sch-countdown__units">
						<div><b data-u="d">00</b><small><?php esc_html_e( 'dni', 'start-namyslow' ); ?></small></div>
						<div><b data-u="h">00</b><small><?php esc_html_e( 'godz.', 'start-namyslow' ); ?></small></div>
						<div><b data-u="m">00</b><small><?php esc_html_e( 'min.', 'start-namyslow' ); ?></small></div>
						<div><b data-u="s">00</b><small><?php esc_html_e( 'sek.', 'start-namyslow' ); ?></small></div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $played_ids || $next_id || $upcoming_ids ) : ?>
			<div class="sch-slider">
				<div class="sch-track" tabindex="0" aria-label="<?php esc_attr_e( 'Terminarz meczów – przewiń w bok', 'start-namyslow' ); ?>">
					<?php
					foreach ( $played_ids as $id ) {
						echo sn_schedule_card( (int) $id, false ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					if ( $next_id ) {
						echo sn_schedule_card( $next_id, true ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					foreach ( array_slice( $upcoming_ids, 0, 4 ) as $id ) {
						echo sn_schedule_card( (int) $id, false ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
				</div>
				<div class="sch-dots" aria-hidden="true"></div>
			</div>
		<?php else : ?>
			<p class="sch__empty"><?php esc_html_e( 'Brak meczów w terminarzu. Dodaj je w panelu: Mecze → Dodaj mecz.', 'start-namyslow' ); ?></p>
		<?php endif; ?>

		<div class="sch__more">
			<a class="btn" href="<?php echo esc_url( get_post_type_archive_link( 'mecz' ) ); ?>"><?php esc_html_e( 'Wszystkie mecze', 'start-namyslow' ); ?></a>
		</div>
	</div>
</section>

<!-- Aktualności -->
<section class="section">
	<div class="container">
		<div class="section__head">
			<h2 class="section__title"><?php esc_html_e( 'Aktualności', 'start-namyslow' ); ?></h2>
			<a class="btn" href="<?php echo esc_url( get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' ) ); ?>"><?php esc_html_e( 'Wszystkie wiadomości', 'start-namyslow' ); ?></a>
		</div>
		<?php
		$news = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 5, 'ignore_sticky_posts' => true ) );
		if ( $news->have_posts() ) :
			?>
			<div class="news-grid">
				<?php
				while ( $news->have_posts() ) :
					$news->the_post();
					get_template_part( 'template-parts/card', null, array( 'fallback' => $news->current_post ) );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Brak wpisów. Dodaj pierwszą aktualność w panelu: Wpisy → Dodaj nowy.', 'start-namyslow' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- Sklep -->
<section class="section section--shop" id="sklep">
	<div class="container">
		<div class="section__head">
			<h2 class="section__title"><?php esc_html_e( 'Sklep', 'start-namyslow' ); ?></h2>
		</div>
		<a class="shop-banner" href="<?php echo esc_url( sn_shop_url() ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/shop-banner.jpg' ); ?>" alt="<?php esc_attr_e( 'Koszulka NKS Start Namysłów – sklep klubowy', 'start-namyslow' ); ?>" loading="lazy">
			<span class="shop-banner__cta"><?php esc_html_e( 'Oficjalny sklep klubowy', 'start-namyslow' ); ?> <b><?php esc_html_e( 'Zobacz w sklepie →', 'start-namyslow' ); ?></b></span>
		</a>
	</div>
</section>

<!-- Tabela + ostatnie mecze -->
<section class="section section--dark">
	<div class="container">
		<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:40px;align-items:start">
			<div>
				<div class="section__head">
					<h2 class="section__title"><?php echo esc_html( sn_opt( 'sn_table_title', 'Tabela IV ligi' ) ); ?></h2>
				</div>
				<?php if ( ! sn_render_table_embed() ) : ?>
				<div class="table-wrap">
					<table class="league-table">
						<thead>
							<tr><th>#</th><th><?php esc_html_e( 'Drużyna', 'start-namyslow' ); ?></th><th>M</th><th>Pkt</th><th>Z</th><th>R</th><th>P</th><th><?php esc_html_e( 'Bramki', 'start-namyslow' ); ?></th></tr>
						</thead>
						<tbody>
							<?php foreach ( sn_table_rows() as $i => $row ) : ?>
								<tr class="<?php echo false !== stripos( $row[0], 'start nam' ) ? 'is-us' : ''; ?>">
									<td><?php echo esc_html( $i + 1 ); ?></td>
									<td><?php echo esc_html( $row[0] ); ?></td>
									<td><?php echo esc_html( $row[1] ); ?></td>
									<td class="pts"><?php echo esc_html( $row[2] ); ?></td>
									<td><?php echo esc_html( $row[3] ); ?></td>
									<td><?php echo esc_html( $row[4] ); ?></td>
									<td><?php echo esc_html( $row[5] ); ?></td>
									<td><?php echo esc_html( $row[6] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
					<?php endif; ?>
			</div>
			<div>
				<div class="section__head">
					<h2 class="section__title"><?php esc_html_e( 'Ostatnie wyniki', 'start-namyslow' ); ?></h2>
				</div>
				<div class="match-list">
					<?php
					$recent = new WP_Query( array(
						'post_type'      => 'mecz',
						'posts_per_page' => 4,
						'meta_key'       => '_sn_date',
						'orderby'        => 'meta_value',
						'order'          => 'DESC',
						'meta_query'     => array(
							array( 'key' => '_sn_goals_for', 'value' => '', 'compare' => '!=' ),
						),
					) );
					if ( $recent->have_posts() ) :
						while ( $recent->have_posts() ) :
							$recent->the_post();
							sn_render_match( get_the_ID() );
						endwhile;
						wp_reset_postdata();
					else :
						echo '<p>' . esc_html__( 'Brak rozegranych meczów. Dodaj je w panelu: Mecze → Dodaj mecz.', 'start-namyslow' ) . '</p>';
					endif;
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Kadra -->
<section class="section squad">
	<div class="container">
		<div class="section__head">
			<h2 class="section__title"><?php esc_html_e( 'Nasza kadra', 'start-namyslow' ); ?></h2>
			<a class="btn" href="<?php echo esc_url( get_post_type_archive_link( 'zawodnik' ) ); ?>"><?php esc_html_e( 'Cała kadra', 'start-namyslow' ); ?></a>
		</div>
		<?php
		$players = new WP_Query( array(
			'post_type'      => 'zawodnik',
			'posts_per_page' => 4,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		) );
		if ( $players->have_posts() ) :
			?>
			<div class="squad-track">
				<?php
				while ( $players->have_posts() ) :
					$players->the_post();
					get_template_part( 'template-parts/player' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Dodaj zawodników w panelu: Kadra → Dodaj zawodnika.', 'start-namyslow' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- Sponsorzy -->
<section class="section section--light">
	<div class="container">
		<div class="section__head">
			<h2 class="section__title"><?php esc_html_e( 'Sponsorzy i partnerzy', 'start-namyslow' ); ?></h2>
		</div>
		<div class="sponsors">
			<?php
			$raw = (string) sn_opt( 'sn_sponsors', "Sponsor strategiczny\nSponsor główny\nPartner\nPartner" );
			foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
				$line = trim( $line );
				if ( '' === $line ) {
					continue;
				}
				$parts = array_map( 'trim', explode( '|', $line, 2 ) );
				echo '<div class="sponsor">';
				if ( ! empty( $parts[1] ) ) {
					echo '<img src="' . esc_url( $parts[1] ) . '" alt="' . esc_attr( $parts[0] ) . '">';
				} else {
					echo esc_html( $parts[0] );
				}
				echo '</div>';
			}
			?>
		</div>
	</div>
</section>

<?php
get_footer();
