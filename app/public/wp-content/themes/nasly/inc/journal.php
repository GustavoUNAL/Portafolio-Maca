<?php
/**
 * Visual Journal estático — contenido + medios en el tema (sin DB).
 *
 * @package Nasly
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL de un asset dentro de assets/maca/.
 */
function maca_asset( string $file ): string {
	return get_template_directory_uri() . '/assets/maca/' . ltrim( $file, '/' );
}

/**
 * Entradas del Visual Journal (orden editorial).
 *
 * @return array<int, array<string, mixed>>
 */
function maca_journal_entries(): array {
	return array(
		array(
			'slug'    => 'diseno-como-herramienta-de-comunicacion',
			'num'     => '01',
			'title'   => 'Diseño como herramienta de comunicación',
			'excerpt' => 'El diseño va más allá de crear imágenes bonitas. Es una herramienta para transmitir ideas, construir mensajes y conectar con las personas.',
			'thumb'   => 'maca-01-sombra.jpg',
			'body'    => 'duo',
			'lede'    => 'El diseño va más allá de crear imágenes bonitas. Es una herramienta para transmitir ideas, construir mensajes y conectar con las personas a través de colores, formas, imágenes y tipografías.',
			'images'  => array(
				array( 'file' => 'maca-01-sombra.jpg', 'alt' => 'Composición gráfica experimental', 'cap' => 'Composición gráfica experimental', 'class' => 'maca-duo__a' ),
				array( 'file' => 'maca-01-grito.jpg', 'alt' => 'Cartel editorial con tipografía expresiva', 'cap' => 'Cartel editorial con tipografía expresiva', 'class' => 'maca-duo__b' ),
			),
			'layout'  => 'maca-duo maca-duo--split',
		),
		array(
			'slug'    => 'sobre-mi-maca',
			'num'     => '02',
			'title'   => 'Sobre mí',
			'excerpt' => 'Soy Maca, una persona curiosa y creativa, interesada en el diseño, la comunicación y las diferentes formas de expresión visual.',
			'thumb'   => 'maca-perfil.jpg',
			'body'    => 'perfil',
			'lede'    => 'Soy Maca, una persona curiosa y creativa, interesada en el diseño, la comunicación y las diferentes formas de expresión visual. Me gusta experimentar, explorar ideas y transformar conceptos en experiencias gráficas.',
			'images'  => array(
				array( 'file' => 'maca-perfil.jpg', 'alt' => 'Retrato de Maca', 'cap' => 'Perfil · Maca', 'class' => 'maca-perfil' ),
			),
			'layout'  => '',
		),
		array(
			'slug'    => 'mi-proceso-creativo',
			'num'     => '03',
			'title'   => 'Mi proceso creativo',
			'excerpt' => 'Cada proyecto comienza con una idea. Investigar, buscar referentes y experimentar forman parte de un proceso en constante transformación.',
			'thumb'   => 'maca-03-proceso.gif',
			'body'    => 'duo',
			'lede'    => 'Cada proyecto comienza con una idea. Investigar, buscar referentes, experimentar con formas, colores y tipografías son parte de un proceso en constante transformación.',
			'images'  => array(
				array( 'file' => 'maca-03-proceso.gif', 'alt' => 'Moodboard de inspiración', 'cap' => 'Moodboard de inspiración', 'class' => 'maca-duo__a' ),
				array( 'file' => 'maca-03-bocetos.jpg', 'alt' => 'Bocetos y experimentaciones', 'cap' => 'Bocetos y experimentaciones', 'class' => 'maca-duo__b' ),
			),
			'layout'  => 'maca-duo maca-duo--wide-top',
		),
		array(
			'slug'    => 'importancia-de-construir-una-identidad-visual',
			'num'     => '04',
			'title'   => 'La importancia de construir una identidad visual',
			'excerpt' => 'Una identidad visual permite expresar la personalidad de una marca y comunicar sus valores de forma coherente.',
			'thumb'   => 'maca-04-bora-identidad.jpg',
			'body'    => 'duo',
			'lede'    => 'Una identidad visual permite expresar la personalidad de una marca y comunicar sus valores. Cada elemento gráfico contribuye a construir una imagen reconocible y coherente.',
			'images'  => array(
				array( 'file' => 'maca-04-bora-identidad.jpg', 'alt' => 'Moodboard de identidad visual', 'cap' => 'Moodboard de identidad visual', 'class' => 'maca-duo__a' ),
				array( 'file' => 'maca-04-aplicaciones.jpg', 'alt' => 'Aplicaciones de marca', 'cap' => 'Aplicaciones de marca', 'class' => 'maca-duo__b' ),
			),
			'layout'  => 'maca-duo maca-duo--tall-left',
		),
		array(
			'slug'    => 'ilustracion-y-narrativa-visual',
			'num'     => '05',
			'title'   => 'Ilustración y narrativa visual',
			'excerpt' => 'Ilustración 2D y escultura 3D para construir personajes y narrativas visuales.',
			'thumb'   => 'maca-illu-gigante.jpg',
			'body'    => 'illu3d',
			'lede'    => 'Las imágenes también cuentan historias. La ilustración permite construir personajes, representar emociones y comunicar ideas mediante formas, colores y composiciones — en 2D y en 3D.',
			'images'  => array(
				array( 'file' => 'maca-illu-gigante.jpg', 'alt' => 'Ilustración editorial', 'cap' => 'Ilustración editorial', 'class' => 'maca-duo__a' ),
				array( 'file' => 'maca-illu-personajes.jpg', 'alt' => 'Personajes y narrativa', 'cap' => 'Personajes y narrativa visual', 'class' => 'maca-duo__b' ),
			),
			'layout'  => 'maca-duo maca-duo--overlap',
		),
		array(
			'slug'    => 'collage-digital-expresion-emocional',
			'num'     => '06',
			'title'   => 'Collage digital como medio de expresión emocional',
			'excerpt' => 'El collage digital es una forma de explorar y expresar emociones mediante fotografías, texturas, recortes y elementos visuales.',
			'thumb'   => 'maca-collage-grito.jpg',
			'body'    => 'collage',
			'lede'    => 'El collage digital es una forma de explorar y expresar emociones mediante fotografías, texturas, recortes y elementos visuales. Una práctica libre donde los fragmentos se transforman en nuevas narrativas.',
			'images'  => array(
				array( 'file' => 'maca-collage-grito.jpg', 'alt' => 'Collage · Grito', 'cap' => '' ),
				array( 'file' => 'maca-collage-casa.jpg', 'alt' => 'Grito urbano', 'cap' => 'Grito urbano' ),
				array( 'file' => 'maca-collage-nino-mar.jpg', 'alt' => 'Sensibilidad', 'cap' => 'Sensibilidad' ),
				array( 'file' => 'maca-collage-carta-montana.jpg', 'alt' => 'Niño y mar', 'cap' => 'Niño y mar' ),
				array( 'file' => 'maca-collage-huevo.jpg', 'alt' => 'Carta y montaña', 'cap' => 'Carta y montaña' ),
				array( 'file' => 'maca-collage-vestido-rojo.jpg', 'alt' => 'Huevo', 'cap' => 'Huevo' ),
				array( 'file' => 'maca-collage-manos-circulo.jpg', 'alt' => 'Vestido y sol', 'cap' => 'Vestido y sol' ),
				array( 'file' => 'maca-collage-volcan-carta.jpg', 'alt' => 'Manos y círculo', 'cap' => 'Manos y círculo' ),
				array( 'file' => 'maca-collage-nina-desierto.jpg', 'alt' => 'Volcán y carta', 'cap' => 'Volcán y carta' ),
				array( 'file' => 'maca-collage-hongos.jpg', 'alt' => 'Niña en el desierto', 'cap' => 'Niña en el desierto' ),
				array( 'file' => 'maca-collage-despedida.jpg', 'alt' => 'Hongos', 'cap' => 'Hongos' ),
				array( 'file' => 'maca-collage-letargo.jpg', 'alt' => 'Despedida', 'cap' => 'Despedida' ),
				array( 'file' => 'maca-collage-paisaje-casa.jpg', 'alt' => 'Letargo', 'cap' => 'Letargo' ),
				array( 'file' => 'maca-collage-escarabajos.jpg', 'alt' => 'Paisaje y casa', 'cap' => 'Paisaje y casa' ),
			),
			'layout'  => 'maca-collage-gallery',
		),
	);
}

/**
 * Busca una entrada por slug.
 *
 * @return array<string, mixed>|null
 */
function maca_get_entry( string $slug ): ?array {
	foreach ( maca_journal_entries() as $entry ) {
		if ( $entry['slug'] === $slug ) {
			return $entry;
		}
	}
	return null;
}

/**
 * URL pública de una entrada.
 */
function maca_entry_url( string $slug ): string {
	return home_url( '/visual-journal/' . $slug . '/' );
}

/**
 * Figura imagen.
 *
 * @param array<string, string> $img
 */
function maca_render_figure( array $img ): string {
	$class = isset( $img['class'] ) ? ' ' . esc_attr( $img['class'] ) : '';
	$cap   = isset( $img['cap'] ) && $img['cap'] !== ''
		? '<figcaption class="maca-caption">' . esc_html( $img['cap'] ) . '</figcaption>'
		: '';
	return sprintf(
		'<figure class="wp-block-image size-large%s"><img src="%s" alt="%s" loading="lazy" decoding="async"/>%s</figure>',
		$class,
		esc_url( maca_asset( $img['file'] ) ),
		esc_attr( $img['alt'] ?? '' ),
		$cap
	);
}

/**
 * Escenario 3D.
 */
function maca_render_stage3d(): string {
	$items = array(
		array( 'video' => 'maca-3d-criatura.mp4', 'poster' => 'maca-3d-criatura-poster.jpg', 'label' => 'Criatura' ),
		array( 'video' => 'maca-3d-personaje.mp4', 'poster' => 'maca-3d-personaje-poster.jpg', 'label' => 'Personaje' ),
		array( 'video' => 'maca-3d-figura.mp4', 'poster' => 'maca-3d-figura-poster.jpg', 'label' => 'Figura' ),
	);
	$cards = '';
	foreach ( $items as $it ) {
		$cards .= sprintf(
			'<figure class="maca-stage3d__card"><video src="%s" poster="%s" muted playsinline loop preload="metadata" aria-label="%s"></video><figcaption>%s</figcaption></figure>',
			esc_url( maca_asset( $it['video'] ) ),
			esc_url( maca_asset( $it['poster'] ) ),
			esc_attr( $it['label'] . ' 3D Maca' ),
			esc_html( $it['label'] )
		);
	}
	return '<div class="maca-stage3d" data-maca-stage3d>
  <p class="maca-stage3d__label">Maca · Escultura 3D</p>
  <div class="maca-stage3d__scene">
    <div class="maca-stage3d__floor" aria-hidden="true"></div>
    <div class="maca-stage3d__ring">' . $cards . '</div>
  </div>
  <div class="maca-stage3d__controls">
    <button type="button" class="maca-stage3d__btn" data-maca-prev>Anterior</button>
    <div class="maca-stage3d__dots" aria-label="Piezas 3D"></div>
    <button type="button" class="maca-stage3d__btn" data-maca-next>Siguiente</button>
  </div>
</div>';
}

/**
 * Cuerpo de una entrada.
 *
 * @param array<string, mixed> $entry
 */
function maca_render_entry_body( array $entry ): string {
	$html  = '<p class="maca-lede">' . esc_html( $entry['lede'] ) . '</p>';
	$html .= '<hr class="wp-block-separator has-alpha-channel-opacity maca-rule"/>';

	$type = $entry['body'];
	if ( 'perfil' === $type ) {
		$html .= maca_render_figure( $entry['images'][0] );
	} elseif ( 'collage' === $type ) {
		$html .= '<div class="wp-block-group maca-collage-gallery">';
		foreach ( $entry['images'] as $img ) {
			$img['class'] = 'maca-collage-gallery__item';
			$html        .= maca_render_figure( $img );
		}
		$html .= '</div>';
	} elseif ( 'illu3d' === $type ) {
		$html .= maca_render_stage3d();
		$html .= '<hr class="wp-block-separator has-alpha-channel-opacity maca-rule"/>';
		$html .= '<p class="maca-stage3d__label">Ilustración digital</p>';
		$html .= '<div class="wp-block-group ' . esc_attr( $entry['layout'] ) . '">';
		foreach ( $entry['images'] as $img ) {
			$html .= maca_render_figure( $img );
		}
		$html .= '</div>';
	} else {
		$html .= '<div class="wp-block-group ' . esc_attr( $entry['layout'] ) . '">';
		foreach ( $entry['images'] as $img ) {
			$html .= maca_render_figure( $img );
		}
		$html .= '</div>';
	}

	return $html;
}

/**
 * Fila de listado (home / archivo).
 *
 * @param array<string, mixed> $entry
 */
function maca_render_entrada_row( array $entry ): string {
	$url = esc_url( maca_entry_url( $entry['slug'] ) );
	return sprintf(
		'<article class="maca-entrada">
			<div class="maca-entrada__row">
				<div class="maca-entrada__folio">
					<p class="maca-entrada__num" aria-hidden="true"></p>
				</div>
				<div class="maca-entrada__copy">
					<h2 class="maca-entrada__heading"><a href="%s">%s</a></h2>
					<p class="maca-entrada__excerpt">%s</p>
				</div>
				<div class="maca-entrada__media">
					<a href="%s"><img src="%s" alt="%s" loading="lazy" decoding="async"/></a>
				</div>
			</div>
		</article>',
		$url,
		esc_html( $entry['title'] ),
		esc_html( $entry['excerpt'] ),
		$url,
		esc_url( maca_asset( $entry['thumb'] ) ),
		esc_attr( $entry['title'] )
	);
}

/**
 * Listado completo.
 */
function maca_render_journal_list(): string {
	$out = '<div class="maca-journal__query"><div class="maca-journal__list">';
	foreach ( maca_journal_entries() as $entry ) {
		$out .= maca_render_entrada_row( $entry );
	}
	$out .= '</div></div>';
	return $out;
}

/**
 * Shortcodes.
 */
function maca_shortcode_journal_list(): string {
	return maca_render_journal_list();
}
add_shortcode( 'maca_journal', 'maca_shortcode_journal_list' );

/**
 * Rewrites estáticos del journal.
 */
function maca_register_rewrites(): void {
	add_rewrite_rule( '^visual-journal/?$', 'index.php?maca_journal=1', 'top' );
	add_rewrite_rule( '^visual-journal/([^/]+)/?$', 'index.php?maca_entry=$matches[1]', 'top' );
	add_rewrite_rule( '^contacto/?$', 'index.php?maca_page=contacto', 'top' );
	add_rewrite_rule( '^sobre-mi/?$', 'index.php?maca_page=sobre-mi', 'top' );
}
add_action( 'init', 'maca_register_rewrites' );

/**
 * @param string[] $vars Vars.
 * @return string[]
 */
function maca_query_vars( array $vars ): array {
	$vars[] = 'maca_journal';
	$vars[] = 'maca_entry';
	$vars[] = 'maca_page';
	return $vars;
}
add_filter( 'query_vars', 'maca_query_vars' );

/**
 * Plantillas PHP para rutas estáticas + portada forzada.
 *
 * @param string $template Plantilla.
 */
function maca_template_include( string $template ): string {
	$dir = get_template_directory() . '/maca-templates';

	$entry = get_query_var( 'maca_entry' );
	if ( is_string( $entry ) && $entry !== '' ) {
		if ( ! maca_get_entry( $entry ) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			return get_query_template( '404' );
		}
		return $dir . '/entry.php';
	}

	if ( get_query_var( 'maca_journal' ) ) {
		return $dir . '/journal.php';
	}

	$page = get_query_var( 'maca_page' );
	if ( 'contacto' === $page ) {
		return $dir . '/contacto.php';
	}
	if ( 'sobre-mi' === $page ) {
		return $dir . '/sobre-mi.php';
	}

	// Portada / blog index: siempre el Visual Journal estático (nunca "Hello world").
	if ( is_front_page() || is_home() ) {
		return $dir . '/home.php';
	}

	return $template;
}
add_filter( 'template_include', 'maca_template_include', 99 );

/**
 * Borra plantillas FSE guardadas en la DB que pisan los archivos del tema.
 */
function maca_purge_custom_templates(): void {
	$slugs = array( 'front-page', 'home', 'index', 'page-visual-journal', 'single' );
	foreach ( $slugs as $slug ) {
		$posts = get_posts(
			array(
				'post_type'      => array( 'wp_template', 'wp_template_part' ),
				'name'           => $slug,
				'post_status'    => 'any',
				'posts_per_page' => 20,
				'fields'         => 'ids',
			)
		);
		foreach ( $posts as $id ) {
			wp_delete_post( (int) $id, true );
		}
	}

	// También por taxonomía theme nasly.
	$q = new WP_Query(
		array(
			'post_type'      => array( 'wp_template', 'wp_template_part' ),
			'post_status'    => 'any',
			'posts_per_page' => 50,
			'fields'         => 'ids',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => 'nasly',
				),
			),
		)
	);
	foreach ( $q->posts as $id ) {
		$slug = get_post_field( 'post_name', $id );
		if ( in_array( $slug, array( 'front-page', 'home', 'index', 'header', 'page-visual-journal' ), true ) ) {
			wp_delete_post( (int) $id, true );
		}
	}
}

/**
 * Flush rewrites + limpia overrides FSE al actualizar el tema.
 */
function maca_maybe_flush_rewrites(): void {
	$ver = get_option( 'maca_rewrite_ver' );
	if ( NASLY_VERSION !== $ver ) {
		maca_register_rewrites();
		maca_purge_custom_templates();
		flush_rewrite_rules( false );
		update_option( 'maca_rewrite_ver', NASLY_VERSION );
		update_option( 'show_on_front', 'posts' );
	}
}
add_action( 'init', 'maca_maybe_flush_rewrites', 20 );
