<?php
/**
 * NKS Start Namysłów – funkcje motywu.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------
 * Podstawowa konfiguracja motywu
 * ---------------------------------------------------------------- */
function sn_setup() {
	load_theme_textdomain( 'start-namyslow', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 120,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Menu główne', 'start-namyslow' ),
		'footer'  => __( 'Menu w stopce', 'start-namyslow' ),
	) );

	add_image_size( 'sn-card', 800, 500, true );
	add_image_size( 'sn-player', 600, 800, true );
}
add_action( 'after_setup_theme', 'sn_setup' );

function sn_assets() {
	wp_enqueue_style(
		'sn-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap&subset=latin-ext',
		array(),
		null
	);
	wp_enqueue_style( 'sn-style', get_stylesheet_uri(), array( 'sn-fonts' ), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'sn-main', get_template_directory_uri() . '/assets/js/main.js', array(), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'sn_assets' );

function sn_widgets() {
	register_sidebar( array(
		'name'          => __( 'Panel boczny', 'start-namyslow' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'sn_widgets' );

function sn_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'sn_excerpt_length' );

function sn_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'sn_excerpt_more' );

/* ------------------------------------------------------------------
 * Typy treści: Mecze i Zawodnicy
 * ---------------------------------------------------------------- */
function sn_register_post_types() {
	register_post_type( 'mecz', array(
		'labels'       => array(
			'name'               => __( 'Mecze', 'start-namyslow' ),
			'singular_name'      => __( 'Mecz', 'start-namyslow' ),
			'add_new'            => __( 'Dodaj mecz', 'start-namyslow' ),
			'add_new_item'       => __( 'Dodaj nowy mecz', 'start-namyslow' ),
			'edit_item'          => __( 'Edytuj mecz', 'start-namyslow' ),
			'all_items'          => __( 'Wszystkie mecze', 'start-namyslow' ),
			'menu_name'          => __( 'Mecze', 'start-namyslow' ),
		),
		'public'       => true,
		'has_archive'  => 'mecze',
		'rewrite'      => array( 'slug' => 'mecz' ),
		'menu_icon'    => 'dashicons-calendar-alt',
		'menu_position'=> 5,
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'zawodnik', array(
		'labels'       => array(
			'name'               => __( 'Zawodnicy', 'start-namyslow' ),
			'singular_name'      => __( 'Zawodnik', 'start-namyslow' ),
			'add_new'            => __( 'Dodaj zawodnika', 'start-namyslow' ),
			'add_new_item'       => __( 'Dodaj nowego zawodnika', 'start-namyslow' ),
			'edit_item'          => __( 'Edytuj zawodnika', 'start-namyslow' ),
			'all_items'          => __( 'Wszyscy zawodnicy', 'start-namyslow' ),
			'menu_name'          => __( 'Kadra', 'start-namyslow' ),
		),
		'public'       => true,
		'has_archive'  => 'kadra',
		'rewrite'      => array( 'slug' => 'zawodnik' ),
		'menu_icon'    => 'dashicons-groups',
		'menu_position'=> 6,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'sn_register_post_types' );

/** Odświeżenie reguł adresów po aktywacji motywu. */
function sn_flush_rewrite() {
	sn_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'sn_flush_rewrite' );

/* ------------------------------------------------------------------
 * Pola własne (meta boxy)
 * ---------------------------------------------------------------- */
function sn_positions() {
	return array(
		'bramkarz'     => __( 'Bramkarze', 'start-namyslow' ),
		'obronca'      => __( 'Obrońcy', 'start-namyslow' ),
		'pomocnik'     => __( 'Pomocnicy', 'start-namyslow' ),
		'napastnik'    => __( 'Napastnicy', 'start-namyslow' ),
		'trener'       => __( 'Sztab szkoleniowy', 'start-namyslow' ),
		'brak'         => __( 'Zawodnicy', 'start-namyslow' ),
	);
}

function sn_add_meta_boxes() {
	add_meta_box( 'sn_mecz', __( 'Dane meczu', 'start-namyslow' ), 'sn_mecz_box', 'mecz', 'normal', 'high' );
	add_meta_box( 'sn_zawodnik', __( 'Dane zawodnika', 'start-namyslow' ), 'sn_zawodnik_box', 'zawodnik', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sn_add_meta_boxes' );

function sn_mecz_box( $post ) {
	wp_nonce_field( 'sn_save_meta', 'sn_meta_nonce' );
	$date   = get_post_meta( $post->ID, '_sn_date', true );
	$opp    = get_post_meta( $post->ID, '_sn_opponent', true );
	$home   = get_post_meta( $post->ID, '_sn_home', true );
	$gf     = get_post_meta( $post->ID, '_sn_goals_for', true );
	$ga     = get_post_meta( $post->ID, '_sn_goals_against', true );
	$comp   = get_post_meta( $post->ID, '_sn_competition', true );
	$place  = get_post_meta( $post->ID, '_sn_place', true );
	?>
	<table class="form-table">
		<tr><th><label for="sn_date"><?php esc_html_e( 'Data i godzina', 'start-namyslow' ); ?></label></th>
			<td><input type="datetime-local" id="sn_date" name="sn_date" value="<?php echo esc_attr( $date ); ?>"></td></tr>
		<tr><th><label for="sn_opponent"><?php esc_html_e( 'Przeciwnik', 'start-namyslow' ); ?></label></th>
			<td><input type="text" id="sn_opponent" name="sn_opponent" class="regular-text" value="<?php echo esc_attr( $opp ); ?>"></td></tr>
		<tr><th><?php esc_html_e( 'Gospodarz', 'start-namyslow' ); ?></th>
			<td><label><input type="checkbox" name="sn_home" value="1" <?php checked( $home, '1' ); ?>> <?php esc_html_e( 'Mecz u siebie (Start gospodarzem)', 'start-namyslow' ); ?></label></td></tr>
		<tr><th><label for="sn_goals_for"><?php esc_html_e( 'Bramki Startu', 'start-namyslow' ); ?></label></th>
			<td><input type="number" min="0" id="sn_goals_for" name="sn_goals_for" value="<?php echo esc_attr( $gf ); ?>"> <em><?php esc_html_e( 'Zostaw puste, jeśli mecz się nie odbył', 'start-namyslow' ); ?></em></td></tr>
		<tr><th><label for="sn_goals_against"><?php esc_html_e( 'Bramki przeciwnika', 'start-namyslow' ); ?></label></th>
			<td><input type="number" min="0" id="sn_goals_against" name="sn_goals_against" value="<?php echo esc_attr( $ga ); ?>"></td></tr>
		<tr><th><label for="sn_competition"><?php esc_html_e( 'Rozgrywki', 'start-namyslow' ); ?></label></th>
			<td><input type="text" id="sn_competition" name="sn_competition" class="regular-text" placeholder="IV liga, 12. kolejka" value="<?php echo esc_attr( $comp ); ?>"></td></tr>
		<tr><th><label for="sn_place"><?php esc_html_e( 'Miejsce', 'start-namyslow' ); ?></label></th>
			<td><input type="text" id="sn_place" name="sn_place" class="regular-text" value="<?php echo esc_attr( $place ); ?>"></td></tr>
	</table>
	<?php
}

function sn_zawodnik_box( $post ) {
	wp_nonce_field( 'sn_save_meta', 'sn_meta_nonce' );
	$number = get_post_meta( $post->ID, '_sn_number', true );
	$pos    = get_post_meta( $post->ID, '_sn_position', true );
	if ( '' === $pos ) {
		$pos = 'brak';
	}
	?>
	<table class="form-table">
		<tr><th><label for="sn_number"><?php esc_html_e( 'Numer na koszulce', 'start-namyslow' ); ?></label></th>
			<td><input type="number" min="0" max="99" id="sn_number" name="sn_number" value="<?php echo esc_attr( $number ); ?>"></td></tr>
		<tr><th><label for="sn_position"><?php esc_html_e( 'Pozycja', 'start-namyslow' ); ?></label></th>
			<td><select id="sn_position" name="sn_position">
				<?php foreach ( sn_positions() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $pos, $key ); ?>><?php echo esc_html( 'brak' === $key ? __( '— nie określono —', 'start-namyslow' ) : $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
	</table>
	<p><em><?php esc_html_e( 'Zdjęcie zawodnika ustaw jako „Obraz wyróżniający” (najlepiej pionowe, proporcje 3:4). Kolejność kart zmienisz polem „Kolejność” w panelu bocznym.', 'start-namyslow' ); ?></em></p>
	<?php
}

function sn_save_meta( $post_id ) {
	if ( ! isset( $_POST['sn_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sn_meta_nonce'] ) ), 'sn_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type = get_post_type( $post_id );

	if ( 'mecz' === $type ) {
		$text_fields = array(
			'sn_date'        => '_sn_date',
			'sn_opponent'    => '_sn_opponent',
			'sn_competition' => '_sn_competition',
			'sn_place'       => '_sn_place',
		);
		foreach ( $text_fields as $field => $meta_key ) {
			$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			update_post_meta( $post_id, $meta_key, $value );
		}
		update_post_meta( $post_id, '_sn_home', isset( $_POST['sn_home'] ) ? '1' : '0' );
		foreach ( array( 'sn_goals_for' => '_sn_goals_for', 'sn_goals_against' => '_sn_goals_against' ) as $field => $meta_key ) {
			$raw = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			update_post_meta( $post_id, $meta_key, '' === $raw ? '' : absint( $raw ) );
		}
	}

	if ( 'zawodnik' === $type ) {
		$number = isset( $_POST['sn_number'] ) ? sanitize_text_field( wp_unslash( $_POST['sn_number'] ) ) : '';
		update_post_meta( $post_id, '_sn_number', '' === $number ? '' : absint( $number ) );
		$pos = isset( $_POST['sn_position'] ) ? sanitize_key( wp_unslash( $_POST['sn_position'] ) ) : '';
		if ( ! array_key_exists( $pos, sn_positions() ) ) {
			$pos = 'brak';
		}
		update_post_meta( $post_id, '_sn_position', $pos );
	}
}
add_action( 'save_post', 'sn_save_meta' );

/* ------------------------------------------------------------------
 * Funkcje pomocnicze: mecze
 * ---------------------------------------------------------------- */
function sn_match_data( $post_id ) {
	$gf = get_post_meta( $post_id, '_sn_goals_for', true );
	$ga = get_post_meta( $post_id, '_sn_goals_against', true );
	$raw_date = (string) get_post_meta( $post_id, '_sn_date', true );
	$dt       = '' !== $raw_date ? date_create_immutable( $raw_date, wp_timezone() ) : false;
	$ts       = $dt ? $dt->getTimestamp() : 0;
	$played = ( '' !== $gf && '' !== $ga );

	$class = '';
	if ( $played ) {
		$class = ( (int) $gf > (int) $ga ) ? 'is-win' : ( ( (int) $gf < (int) $ga ) ? 'is-loss' : 'is-draw' );
	}

	return array(
		'ts'       => $ts,
		'opponent' => get_post_meta( $post_id, '_sn_opponent', true ),
		'home'     => '1' === get_post_meta( $post_id, '_sn_home', true ),
		'gf'       => $gf,
		'ga'       => $ga,
		'played'   => $played,
		'comp'     => get_post_meta( $post_id, '_sn_competition', true ),
		'place'    => get_post_meta( $post_id, '_sn_place', true ),
		'class'    => $class,
	);
}

/** Wyświetla jeden wiersz meczu. */
function sn_render_match( $post_id ) {
	$m = sn_match_data( $post_id );
	$club = 'NKS Start Namysłów';
	$home_team = $m['home'] ? $club : $m['opponent'];
	$away_team = $m['home'] ? $m['opponent'] : $club;
	if ( $m['played'] ) {
		$score = $m['home'] ? $m['gf'] . ' : ' . $m['ga'] : $m['ga'] . ' : ' . $m['gf'];
	} else {
		$score = $m['ts'] ? wp_date( 'H:i', $m['ts'] ) : '– : –';
	}
	?>
	<div class="match <?php echo esc_attr( $m['class'] ); ?>">
		<div class="match__date">
			<?php if ( $m['ts'] ) : ?>
				<strong><?php echo esc_html( wp_date( 'd.m.Y', $m['ts'] ) ); ?></strong>
				<?php echo esc_html( wp_date( 'l', $m['ts'] ) ); ?>
			<?php endif; ?>
		</div>
		<div class="match__teams">
			<?php echo esc_html( $home_team . ' – ' . $away_team ); ?>
			<span class="match__comp"><?php echo esc_html( trim( $m['comp'] . ( $m['place'] ? ' • ' . $m['place'] : '' ), ' •' ) ); ?></span>
		</div>
		<div class="match__score <?php echo $m['played'] ? '' : 'match__score--upcoming'; ?>"><?php echo esc_html( $score ); ?></div>
	</div>
	<?php
}

/** Najbliższy nierozegrany mecz. */
function sn_next_match() {
	$q = new WP_Query( array(
		'post_type'      => 'mecz',
		'posts_per_page' => 1,
		'meta_key'       => '_sn_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_sn_date',
				'value'   => wp_date( 'Y-m-d\TH:i', current_time( 'timestamp' ) - 3 * HOUR_IN_SECONDS ),
				'compare' => '>=',
				'type'    => 'CHAR',
			),
		),
	) );
	return $q;
}

/* ------------------------------------------------------------------
 * Personalizator (Wygląd → Dostosuj)
 * ---------------------------------------------------------------- */
function sn_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'sn_club', array(
		'title'    => __( 'Dane klubu', 'start-namyslow' ),
		'priority' => 30,
	) );

	$fields = array(
		'sn_hero_kicker'  => array( __( 'Hero – mała etykieta', 'start-namyslow' ), 'IV liga • sezon 2026/27', 'text' ),
		'sn_hero_title'   => array( __( 'Hero – nagłówek', 'start-namyslow' ), 'NKS Start Namysłów', 'text' ),
		'sn_hero_text'    => array( __( 'Hero – opis', 'start-namyslow' ), 'Oficjalna strona klubu piłkarskiego z Namysłowa. Wyniki, aktualności, kadra i terminarz.', 'textarea' ),
		'sn_address'      => array( __( 'Adres stadionu / siedziby', 'start-namyslow' ), 'ul. [adres stadionu], 46-100 Namysłów', 'text' ),
		'sn_email'        => array( __( 'E-mail klubu', 'start-namyslow' ), 'kontakt@example.com', 'text' ),
		'sn_phone'        => array( __( 'Telefon', 'start-namyslow' ), '+48 000 000 000', 'text' ),
		'sn_facebook'     => array( __( 'Link do Facebooka', 'start-namyslow' ), '', 'url' ),
		'sn_instagram'    => array( __( 'Link do Instagrama', 'start-namyslow' ), '', 'url' ),
		'sn_youtube'      => array( __( 'Link do YouTube', 'start-namyslow' ), '', 'url' ),
		'sn_sponsors'     => array( __( 'Sponsorzy (jedna nazwa na linię; opcjonalnie „Nazwa|adres_obrazka”)', 'start-namyslow' ), "Sponsor strategiczny\nSponsor główny\nPartner\nPartner", 'textarea' ),
		'sn_table'        => array(
			__( 'Tabela ligowa (linia: Drużyna;M;Pkt;Z;R;P;Bramki)', 'start-namyslow' ),
			"OKS ODRA II OPOLE;12;36;12;0;0;45:10\nLZS STAROWICE DOLNE;11;33;11;0;0;42:7\nMKS RUCH ZDZIESZOWICE;12;21;6;3;3;31:18\nLZS STAROŚCIN;12;21;7;0;5;34:27\nLZS ADAMIETZ KADŁUB;12;19;6;1;5;40:31\nLZS DOMASZKOWICE;12;19;5;4;3;21:13\nNKS START NAMYSŁÓW;12;19;6;1;5;26:26\nKS MAŁAPANEW OZIMEK;12;16;5;1;6;24:27\nLKS PO-RA-WIE WIĘKSZYCE;12;11;3;2;7;18:31\nSSKS STAL ZAWADZKIE;12;10;3;1;8;14:30\nMKS GOGOLIN;12;10;3;1;8;18:38\nLKS VICTORIA ŻYROWA;12;9;2;3;7;16:42\nKS FORTUNA GŁOGÓWEK;11;8;2;2;7;17:28\nLZS ŚLĄSK ŁUBNIANY;12;7;2;1;9;21:39",
			'textarea',
		),
		'sn_table_title'  => array( __( 'Tytuł tabeli', 'start-namyslow' ), 'Tabela IV ligi', 'text' ),
	);

	foreach ( $fields as $id => $f ) {
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $f[2] ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'url' === $f[2] ) {
			$sanitize = 'esc_url_raw';
		}
		$wp_customize->add_setting( $id, array(
			'default'           => $f[1],
			'sanitize_callback' => $sanitize,
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $f[0],
			'section' => 'sn_club',
			'type'    => 'url' === $f[2] ? 'url' : $f[2],
		) );
	}

	$wp_customize->add_setting( 'sn_table_embed', array(
		'default'           => '',
		'sanitize_callback' => 'sn_sanitize_embed',
	) );
	$wp_customize->add_control( 'sn_table_embed', array(
		'label'       => __( 'Tabela z zewnętrznego serwisu – kod osadzenia (iframe) lub adres https://', 'start-namyslow' ),
		'description' => __( 'Wklej kod iframe/widżetu albo sam adres https:// strony z tabelą. Jeśli pole jest wypełnione, zastępuje tabelę wpisywaną ręcznie powyżej. Zostaw puste, aby użyć tabeli ręcznej.', 'start-namyslow' ),
		'section'     => 'sn_club',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'sn_table_embed_height', array(
		'default'           => 520,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sn_table_embed_height', array(
		'label'       => __( 'Wysokość osadzonej tabeli (px) – dotyczy wklejonego adresu', 'start-namyslow' ),
		'section'     => 'sn_club',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 200, 'max' => 2000, 'step' => 10 ),
	) );

	for ( $n = 1; $n <= 3; $n++ ) {
		$wp_customize->add_setting( 'sn_hero_image_' . $n, array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sn_hero_image_' . $n, array(
			/* translators: %d: numer zdjęcia */
			'label'   => sprintf( __( 'Baner – zdjęcie %d (opcjonalnie, zastępuje wbudowane)', 'start-namyslow' ), $n ),
			'section' => 'sn_club',
		) ) );
	}

	$wp_customize->add_setting( 'sn_shop_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'sn_shop_url', array(
		'label'       => __( 'Sklep – adres podstrony (puste = strona „Sklep”)', 'start-namyslow' ),
		'section'     => 'sn_club',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'sn_hero_interval', array( 'default' => 5, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'sn_hero_interval', array(
		'label'       => __( 'Baner – czas zmiany zdjęcia (sekundy)', 'start-namyslow' ),
		'section'     => 'sn_club',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 2, 'max' => 30 ),
	) );
}
add_action( 'customize_register', 'sn_customize_register' );

/** Adres podstrony sklepu: z Personalizatora, a gdy pusty – strona o adresie /sklep/. */
function sn_shop_url() {
	$custom = trim( (string) get_theme_mod( 'sn_shop_url', '' ) );
	if ( '' !== $custom ) {
		return $custom;
	}
	$page = get_page_by_path( 'sklep' );
	return $page ? get_permalink( $page ) : home_url( '/sklep/' );
}

/** Skrót do pobierania ustawień z wartością domyślną. */
function sn_opt( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/** Tabela ligowa – parsowanie ustawienia. */
function sn_table_rows() {
	$default = "OKS ODRA II OPOLE;12;36;12;0;0;45:10\nLZS STAROWICE DOLNE;11;33;11;0;0;42:7\nMKS RUCH ZDZIESZOWICE;12;21;6;3;3;31:18\nLZS STAROŚCIN;12;21;7;0;5;34:27\nLZS ADAMIETZ KADŁUB;12;19;6;1;5;40:31\nLZS DOMASZKOWICE;12;19;5;4;3;21:13\nNKS START NAMYSŁÓW;12;19;6;1;5;26:26\nKS MAŁAPANEW OZIMEK;12;16;5;1;6;24:27\nLKS PO-RA-WIE WIĘKSZYCE;12;11;3;2;7;18:31\nSSKS STAL ZAWADZKIE;12;10;3;1;8;14:30\nMKS GOGOLIN;12;10;3;1;8;18:38\nLKS VICTORIA ŻYROWA;12;9;2;3;7;16:42\nKS FORTUNA GŁOGÓWEK;11;8;2;2;7;17:28\nLZS ŚLĄSK ŁUBNIANY;12;7;2;1;9;21:39";
	$raw  = (string) get_theme_mod( 'sn_table', $default );
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = array_map( 'trim', explode( ';', $line ) );
		$parts = array_pad( $parts, 7, '' );
		$rows[] = $parts;
	}
	return $rows;
}

/** Herb zastępczy (SVG) używany, gdy nie ustawiono logo. */
function sn_crest_svg() {
	return '<svg class="crest" viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="NKS Start Namysłów">'
		. '<path d="M50 4 94 18v44c0 28-20 46-44 56C26 108 6 90 6 62V18z" fill="#d3121c" stroke="#fff" stroke-width="4"/>'
		. '<path d="M50 4 94 18v44c0 28-20 46-44 56z" fill="#0d0d0f" opacity=".92"/>'
		. '<text x="50" y="58" text-anchor="middle" font-family="Oswald,Arial,sans-serif" font-size="30" font-weight="700" fill="#fff">NKS</text>'
		. '<text x="50" y="86" text-anchor="middle" font-family="Oswald,Arial,sans-serif" font-size="19" font-weight="600" fill="#fff">START</text>'
		. '</svg>';
}

/** Menu zapasowe, gdy nie utworzono menu w panelu. */
function sn_fallback_menu() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Start', 'start-namyslow' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ) . '">' . esc_html__( 'Aktualności', 'start-namyslow' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'mecz' ) ) . '">' . esc_html__( 'Mecze', 'start-namyslow' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'zawodnik' ) ) . '">' . esc_html__( 'Kadra', 'start-namyslow' ) . '</a></li>';
	echo '</ul>';
}

/** Dozwolone znaczniki osadzenia dla użytkowników bez uprawnienia unfiltered_html. */
function sn_embed_allowed_html() {
	return array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'title'           => true,
			'style'           => true,
			'loading'         => true,
			'scrolling'       => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		),
	);
}

/** Czyszczenie kodu osadzenia przy zapisie ustawienia. */
function sn_sanitize_embed( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	// Administrator (unfiltered_html) może wkleić dowolny widżet, także ze skryptem.
	if ( current_user_can( 'unfiltered_html' ) ) {
		return $value;
	}
	// Pozostali: tylko iframe albo sam adres https://.
	if ( 0 === strpos( $value, 'https://' ) && false === strpos( $value, '<' ) ) {
		return esc_url_raw( $value );
	}
	return wp_kses( $value, sn_embed_allowed_html() );
}

/**
 * Wypisuje osadzoną tabelę, jeśli ustawiono. Zwraca false, gdy pole jest puste
 * (wtedy motyw pokazuje tabelę wpisaną ręcznie).
 */
function sn_render_table_embed() {
	$code = trim( (string) get_theme_mod( 'sn_table_embed', '' ) );
	if ( '' === $code ) {
		return false;
	}

	// Sam adres https:// -> owijamy w iframe.
	if ( 0 === strpos( $code, 'https://' ) && false === strpos( $code, '<' ) ) {
		$height = max( 200, (int) get_theme_mod( 'sn_table_embed_height', 520 ) );
		printf(
			'<div class="table-embed"><iframe src="%1$s" title="%2$s" loading="lazy" style="height:%3$dpx"></iframe></div>',
			esc_url( $code ),
			esc_attr( get_theme_mod( 'sn_table_title', 'Tabela IV ligi' ) ),
			(int) $height
		);
		return true;
	}

	// Gotowy kod osadzenia – był oczyszczony przy zapisie w Personalizatorze.
	echo '<div class="table-embed">' . $code . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	return true;
}

/** Domyślna ikona karty przeglądarki (herb klubu), gdy nie ustawiono ikony witryny. */
function sn_default_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="icon" type="image/png" href="' . esc_url( get_template_directory_uri() . '/assets/images/favicon.png' ) . '">' . "\n";
}
add_action( 'wp_head', 'sn_default_favicon', 5 );

/**
 * Herb drużyny dla sekcji „Najbliższy mecz”.
 * - nasz klub: logo z motywu,
 * - przeciwnik: obraz wyróżniający meczu (Mecze → edycja → Obraz wyróżniający),
 *   albo plik assets/images/crests/{nazwa-przeciwnika}.png (np. ks-fortuna-glogowek.png),
 *   a gdy brak – okrągła plakietka z inicjałami.
 */
function sn_team_crest( $is_us, $opponent_name, $post_id ) {
	$base = get_template_directory_uri() . '/assets/images/';

	if ( $is_us ) {
		return '<img class="next-match__crest" src="' . esc_url( $base . 'logo.png' ) . '" alt="NKS Start Namysłów" width="96" height="96">';
	}

	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, 'medium', array( 'class' => 'next-match__crest', 'alt' => $opponent_name ) );
	}

	$slug = sanitize_title( $opponent_name );
	if ( $slug && file_exists( get_template_directory() . '/assets/images/crests/' . $slug . '.png' ) ) {
		return '<img class="next-match__crest" src="' . esc_url( $base . 'crests/' . $slug . '.png' ) . '" alt="' . esc_attr( $opponent_name ) . '" width="96" height="96">';
	}

	// Plakietka z inicjałami (pomijamy przedrostki klubów: KS, LZS, MKS…).
	$clean    = preg_replace( '/\b(KS|LZS|MKS|LKS|NKS|OKS|SSKS|KP|GKS|UKS)\b/iu', '', $opponent_name );
	$words    = array_values( array_filter( preg_split( '/\s+/u', trim( (string) $clean ) ) ) );
	$initials = '';
	foreach ( array_slice( $words, 0, 2 ) as $w ) {
		$initials .= mb_strtoupper( mb_substr( $w, 0, 1 ) );
	}
	if ( '' === $initials ) {
		$initials = '?';
	}
	return '<span class="next-match__crest next-match__crest--placeholder" aria-hidden="true">' . esc_html( $initials ) . '</span>';
}

/**
 * Po aktywacji motywu dodaje pierwszy mecz (13. kolejka), jeśli nie ma jeszcze żadnych meczów.
 * Kolejne mecze dodajesz w panelu: Mecze → Dodaj mecz.
 */
function sn_seed_first_match() {
	// Terminarz i wyniki rundy jesiennej 2026/27 (IV liga opolska), źródło: oficjalny terminarz (PDF, stan z 28.10.2026).
	$home_ground = 'Stadion Sportowy (Namysłów, Kazimierza Pułaskiego 5)';
	$matches     = array(
		// data,              przeciwnik,                  u siebie, gole Startu, gole rywala, kolejka, miejsce
		array( '2026-08-08T17:00', 'LKS Po-Ra-Wie Większyce', 1, 4, 1, 1, $home_ground ),
		array( '2026-08-15T15:00', 'LZS Domaszkowice',        0, 1, 2, 2, 'LZS Domaszkowice (Domaszkowice 81)' ),
		array( '2026-08-22T13:00', 'LZS Starościn',           0, 2, 1, 3, 'Stadion Starościn (Starościn, Kluczborska 4)' ),
		array( '2026-08-29T13:00', 'LKS Victoria Żyrowa',     0, 0, 1, 4, 'Miejski Stadion Sportowy (Zdzieszowice, Rozwadzka 1)' ),
		array( '2026-09-05T16:30', 'KS Małapanew Ozimek',     1, 1, 6, 5, $home_ground ),
		array( '2026-09-12T16:30', 'LZS Śląsk Łubniany',      0, 5, 1, 6, 'Boisko Sportowe w Łubnianach (Łubniany, Opolska 1)' ),
		array( '2026-09-19T16:00', 'MKS Gogolin',             1, 4, 2, 7, $home_ground ),
		array( '2026-09-26T13:00', 'SSKS Stal Zawadzkie',     0, 1, 0, 8, 'Stadion Miejski w Zawadzkiem (Zawadzkie, Opolska 23)' ),
		array( '2026-10-04T14:00', 'OKS Odra II Opole',       1, 1, 4, 9, $home_ground ),
		array( '2026-10-10T15:30', 'MKS Ruch Zdzieszowice',   1, 2, 2, 10, $home_ground ),
		array( '2026-10-17T15:00', 'LZS Starowice Dolne',     0, 1, 4, 11, 'Starowice Dolne (Starowice Dolne 36)' ),
		array( '2026-10-24T14:30', 'LZS Adamietz Kadłub',     1, 4, 2, 12, $home_ground ),
		array( '2026-10-31T14:00', 'KS Fortuna Głogówek',     0, '', '', 13, 'Stadion Miejski w Głogówku – FORTUNA (Głogówek, Damrota 12)' ),
		// Kolejne mecze – tymczasowe (przeciwnik, termin i miejsce do uzupełnienia w panelu: Mecze → edycja).
		array( '2026-11-07T14:00', 'Przeciwnik do ustalenia', 1, '', '', 14, 'Miejsce do ustalenia' ),
		array( '2026-11-14T14:00', 'Przeciwnik do ustalenia', 0, '', '', 15, 'Miejsce do ustalenia' ),
		array( '2026-11-21T14:00', 'Przeciwnik do ustalenia', 1, '', '', 16, 'Miejsce do ustalenia' ),
	);

	foreach ( $matches as $m ) {
		$comp   = 'IV liga, ' . $m[5] . '. kolejka';
		$exists = get_posts( array(
			'post_type'   => 'mecz',
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array(
				array( 'key' => '_sn_opponent', 'value' => $m[1] ),
				array( 'key' => '_sn_competition', 'value' => $comp ),
			),
		) );
		if ( $exists ) {
			// Mecz już jest (np. z wcześniejszej wersji motywu) – popraw tylko termin i miejsce.
			update_post_meta( $exists[0], '_sn_date', $m[0] );
			update_post_meta( $exists[0], '_sn_place', $m[6] );
			continue;
		}
		$home  = (bool) $m[2];
		$title = $home ? 'NKS Start Namysłów – ' . $m[1] : $m[1] . ' – NKS Start Namysłów';
		$id    = wp_insert_post( array(
			'post_type'   => 'mecz',
			'post_status' => 'publish',
			'post_title'  => $title,
		) );
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		update_post_meta( $id, '_sn_date', $m[0] );
		update_post_meta( $id, '_sn_opponent', $m[1] );
		update_post_meta( $id, '_sn_home', $home ? '1' : '0' );
		update_post_meta( $id, '_sn_goals_for', $m[3] );
		update_post_meta( $id, '_sn_goals_against', $m[4] );
		update_post_meta( $id, '_sn_competition', $comp );
		update_post_meta( $id, '_sn_place', $m[6] );
	}
}

/**
 * Dodaje czterech zawodników ze zdjęciami (tylko raz, tylko gdy nie ma jeszcze żadnych).
 * Pozycje i numery uzupełnisz w panelu: Kadra → edycja zawodnika.
 */
function sn_seed_players() {
	if ( get_option( 'sn_seed_players_done' ) ) {
		return;
	}
	update_option( 'sn_seed_players_done', '1' );

	$existing = get_posts( array(
		'post_type'   => 'zawodnik',
		'post_status' => 'any',
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( $existing ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$players = array(
		array( 'Piotr Raduchowski', 'piotr-raduchowski', 8 ),
		array( 'Mikołaj Liberda', 'mikolaj-liberda', 11 ),
		array( 'Mikołaj Kudra', 'mikolaj-kudra', 4 ),
		array( 'Patryk Maciołek', 'patryk-maciolek', 17 ),
	);

	foreach ( $players as $i => $p ) {
		$id = wp_insert_post( array(
			'post_type'   => 'zawodnik',
			'post_status' => 'publish',
			'post_title'  => $p[0],
			'menu_order'  => $i + 1,
		) );
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		update_post_meta( $id, '_sn_position', 'brak' );
		update_post_meta( $id, '_sn_number', $p[2] ); // numer tymczasowy – zmień w panelu

		$src = get_template_directory() . '/assets/images/players/' . $p[1] . '.jpg';
		if ( ! file_exists( $src ) ) {
			continue;
		}
		$tmp = wp_tempnam( $src );
		if ( $tmp && copy( $src, $tmp ) ) {
			$att = media_handle_sideload( array( 'name' => $p[1] . '.jpg', 'tmp_name' => $tmp ), $id, $p[0] );
			if ( is_wp_error( $att ) ) {
				@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
			} else {
				set_post_thumbnail( $id, $att );
			}
		}
	}
}

/** Pięć wpisów startowych do sekcji Aktualności – edytuj je lub usuń w panelu: Wpisy. */
function sn_seed_news() {
	$posts = array(
		array( 'Zwycięstwo na własnym boisku', 'W 12. kolejce IV ligi NKS Start Namysłów pokonał LZS Adamietz Kadłub 4:2. Tu możesz dodać pełną relację, komentarze trenera i zawodników oraz zdjęcia z meczu.' ),
		array( 'Zapowiedź kolejnej kolejki', 'W sobotę 31 października o godz. 14:00 Start zagra na wyjeździe z KS Fortuna Głogówek (13. kolejka). Tu możesz dodać zapowiedź spotkania.' ),
		array( 'Aktualność startowa 3', 'To przykładowy wpis. Zmień tytuł i treść albo usuń go w panelu: Wpisy.' ),
		array( 'Aktualność startowa 4', 'To przykładowy wpis. Zmień tytuł i treść albo usuń go w panelu: Wpisy.' ),
		array( 'Aktualność startowa 5', 'To przykładowy wpis. Zmień tytuł i treść albo usuń go w panelu: Wpisy.' ),
	);
	foreach ( $posts as $i => $p ) {
		$when = time() - $i * HOUR_IN_SECONDS;
		wp_insert_post( array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => $p[0],
			'post_content'  => '<p>' . $p[1] . '</p>',
			'post_excerpt'  => $p[1],
			'post_date'     => wp_date( 'Y-m-d H:i:s', $when ),
			'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $when ),
		) );
	}
}

/** Jednorazowe dane startowe – uruchamiane po wejściu administratora do panelu. */
function sn_maybe_seed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! get_option( 'sn_seed_matches_v4' ) ) {
		update_option( 'sn_seed_matches_v4', '1' );
		sn_seed_first_match();
	}
	sn_seed_players();
	if ( ! get_option( 'sn_seed_news_v1' ) ) {
		update_option( 'sn_seed_news_v1', '1' );
		sn_seed_news();
	}
	if ( ! get_option( 'sn_seed_shop_v1' ) ) {
		update_option( 'sn_seed_shop_v1', '1' );
		if ( ! get_page_by_path( 'sklep' ) ) {
			wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'Sklep',
				'post_name'    => 'sklep',
				'post_content' => '<p>Oficjalny sklep klubowy NKS Start Namysłów. Tu dodaj opis, ceny i linki do zamówień (edytuj stronę „Sklep”).</p>',
			) );
		}
	}
}
add_action( 'admin_init', 'sn_maybe_seed' );

/** Zdjęcia banera: wbudowane 3 sztuki, z możliwością podmiany w Personalizatorze. */
function sn_hero_slides() {
	$base     = get_template_directory_uri() . '/assets/images/';
	$defaults = array(
		array( 'url' => $base . 'hero-1.jpg', 'pos' => 'center 8%' ),
		array( 'url' => $base . 'hero-2.jpg', 'pos' => 'center 30%' ),
		array( 'url' => $base . 'hero-3.jpg', 'pos' => 'center 35%' ),
	);
	$slides = array();
	foreach ( $defaults as $i => $d ) {
		$custom = get_theme_mod( 'sn_hero_image_' . ( $i + 1 ) );
		$slides[] = $custom ? array( 'url' => $custom, 'pos' => 'center center' ) : $d;
	}
	return $slides;
}

/** Najbliższe mecze (od teraz, z 3-godzinną rezerwą na mecz w trakcie), rosnąco po dacie. */
function sn_upcoming_matches( $limit = 5 ) {
	return new WP_Query( array(
		'post_type'      => 'mecz',
		'posts_per_page' => (int) $limit,
		'fields'         => 'ids',
		'meta_key'       => '_sn_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_sn_date',
				'value'   => wp_date( 'Y-m-d\TH:i', time() - 3 * HOUR_IN_SECONDS ),
				'compare' => '>=',
				'type'    => 'CHAR',
			),
		),
	) );
}

/** Ostatnie rozegrane mecze (z wpisanym wynikiem), malejąco po dacie. */
function sn_played_matches( $limit = 4 ) {
	return new WP_Query( array(
		'post_type'      => 'mecz',
		'posts_per_page' => (int) $limit,
		'fields'         => 'ids',
		'meta_key'       => '_sn_date',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => array(
			array( 'key' => '_sn_goals_for', 'value' => '', 'compare' => '!=' ),
			array( 'key' => '_sn_goals_against', 'value' => '', 'compare' => '!=' ),
		),
	) );
}

/**
 * Karta meczu w sekcji „Terminarz” (rozegrany: wynik, nadchodzący: „vs”).
 */
function sn_schedule_card( $post_id, $is_next = false ) {
	$m         = sn_match_data( $post_id );
	$club      = 'NKS Start Namysłów';
	$home_team = $m['home'] ? $club : $m['opponent'];
	$away_team = $m['home'] ? $m['opponent'] : $club;

	if ( $m['played'] ) {
		$center = $m['home'] ? $m['gf'] . ' : ' . $m['ga'] : $m['ga'] . ' : ' . $m['gf'];
	} else {
		$center = 'vs';
	}

	$classes = 'sch-card ' . ( $m['played'] ? 'is-played ' . $m['class'] : 'is-upcoming' ) . ( $is_next ? ' is-next' : '' );

	$html  = '<article class="' . esc_attr( $classes ) . '">';
	if ( $is_next ) {
		$html .= '<span class="sch-card__badge">' . esc_html__( 'Najbliższy mecz', 'start-namyslow' ) . '</span>';
	}
	$html .= '<div class="sch-card__comp">' . esc_html( $m['comp'] ) . '</div>';
	$html .= '<div class="sch-card__date">' . esc_html( $m['ts'] ? wp_date( 'd.m', $m['ts'] ) : '' ) . '</div>';
	$html .= '<div class="sch-card__time">' . esc_html( $m['ts'] ? wp_date( 'l, \g. H:i', $m['ts'] ) : '' ) . '</div>';
	$html .= '<div class="sch-card__teams">';
	$html .= '<div class="sch-team">' . sn_team_crest( $m['home'], $m['opponent'], $post_id ) . '<span>' . esc_html( $home_team ) . '</span></div>';
	$html .= '<div class="sch-card__score">' . esc_html( $center ) . '</div>';
	$html .= '<div class="sch-team">' . sn_team_crest( ! $m['home'], $m['opponent'], $post_id ) . '<span>' . esc_html( $away_team ) . '</span></div>';
	$html .= '</div>';
	$html .= '<div class="sch-card__place">' . esc_html( $m['place'] ) . '</div>';
	$html .= '</article>';
	return $html;
}
