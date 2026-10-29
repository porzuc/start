<?php
/**
 * Nagłówek strony.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#d3121c">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="topbar">
	<div class="container">
		<div class="topbar__info">
			<span><?php echo esc_html( sn_opt( 'sn_address', 'ul. [adres stadionu], 46-100 Namysłów' ) ); ?></span>
			<span><?php echo esc_html( sn_opt( 'sn_phone', '+48 000 000 000' ) ); ?></span>
		</div>
		<div class="socials">
			<?php
			$links = array(
				'Facebook'  => sn_opt( 'sn_facebook' ),
				'Instagram' => sn_opt( 'sn_instagram' ),
				'YouTube'   => sn_opt( 'sn_youtube' ),
			);
			foreach ( $links as $label => $url ) {
				if ( $url ) {
					echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a>';
				}
			}
			?>
		</div>
	</div>
</div>

<header class="site-header">
	<div class="container">
		<a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				echo wp_kses_post( get_custom_logo() );
			} else {
				echo '<img class="sn-logo" src="' . esc_url( get_template_directory_uri() . '/assets/images/logo.png' ) . '" alt="NKS Start Namysłów" width="72" height="72">';
			}
			?>
			<span>
				<span class="site-title">NKS <span>Start</span> Namysłów</span>
				<span class="site-tagline"><?php esc_html_e( 'Klub piłkarski • IV liga', 'start-namyslow' ); ?></span>
			</span>
		</a>

		<button class="nav-toggle" aria-expanded="false" aria-controls="main-nav"><?php esc_html_e( 'Menu', 'start-namyslow' ); ?></button>

		<nav id="main-nav" class="main-nav" aria-label="<?php esc_attr_e( 'Menu główne', 'start-namyslow' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => 'sn_fallback_menu',
				'depth'          => 2,
			) );
			?>
		</nav>
	</div>
</header>
<main id="content">
