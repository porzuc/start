<?php
/**
 * Stopka strony.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<footer class="site-footer">
	<div class="container footer-grid">
		<div>
			<img class="sn-logo sn-logo--footer" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="NKS Start Namysłów" width="90" height="90">
			<h4>NKS Start Namysłów</h4>
			<p><?php echo esc_html( sn_opt( 'sn_hero_text', 'Oficjalna strona klubu piłkarskiego z Namysłowa.' ) ); ?></p>
			<p>
				<?php echo esc_html( sn_opt( 'sn_address', '' ) ); ?><br>
				<?php
				$email = sn_opt( 'sn_email', '' );
				if ( $email ) {
					echo '<a href="mailto:' . esc_attr( antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a>';
				}
				?>
			</p>
		</div>
		<div>
			<h4><?php esc_html_e( 'Na skróty', 'start-namyslow' ); ?></h4>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'fallback_cb'    => 'sn_fallback_menu',
				'depth'          => 1,
			) );
			?>
		</div>
		<div>
			<h4><?php esc_html_e( 'Obserwuj nas', 'start-namyslow' ); ?></h4>
			<ul>
				<?php
				$links = array(
					'Facebook'  => sn_opt( 'sn_facebook' ),
					'Instagram' => sn_opt( 'sn_instagram' ),
					'YouTube'   => sn_opt( 'sn_youtube' ),
				);
				foreach ( $links as $label => $url ) {
					if ( $url ) {
						echo '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a></li>';
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> NKS Start Namysłów. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'start-namyslow' ); ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
