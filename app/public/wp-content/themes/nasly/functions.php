<?php
/**
 * Nasly — funciones del tema.
 *
 * @package Nasly
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NASLY_VERSION', '3.1.0' );

require_once get_template_directory() . '/inc/journal.php';

/**
 * Soporte del tema, estilos de bloque y assets.
 */
function nasly_setup(): void {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );

	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'ficha',
			'label' => 'Ficha',
		)
	);
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'eyebrow',
			'label' => 'Eyebrow',
		)
	);
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'entrada',
			'label' => 'Entrada',
		)
	);
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'ficha-texto',
			'label' => 'Ficha texto',
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'panel',
			'label' => 'Panel',
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'linea',
			'label' => 'Línea',
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'marco',
			'label' => 'Marco',
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'final',
			'label' => 'Final',
		)
	);
	register_block_style(
		'core/gallery',
		array(
			'name'  => 'feed',
			'label' => 'Feed 4:5',
		)
	);
	register_block_style(
		'core/gallery',
		array(
			'name'  => 'feed-1',
			'label' => 'Feed 1:1',
		)
	);
	register_block_style(
		'core/gallery',
		array(
			'name'  => 'marco',
			'label' => 'Marco',
		)
	);
}
add_action( 'after_setup_theme', 'nasly_setup' );

/**
 * Encola CSS y JS del tema.
 */
function nasly_enqueue_assets(): void {
	wp_enqueue_style(
		'nasly',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		NASLY_VERSION
	);

	wp_enqueue_script(
		'nasly-comparador',
		get_template_directory_uri() . '/assets/js/comparador.js',
		array(),
		NASLY_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_enqueue_script(
		'nasly-motion',
		get_template_directory_uri() . '/assets/js/motion.js',
		array(),
		NASLY_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_enqueue_script(
		'nasly-stage3d',
		get_template_directory_uri() . '/assets/js/stage3d.js',
		array(),
		NASLY_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'nasly_enqueue_assets' );

/**
 * Mantener GIFs animados: no usar tamaños intermedios estáticos.
 */
function nasly_keep_gif_animation( $out, $id ) {
	$mime = get_post_mime_type( $id );
	if ( 'image/gif' !== $mime ) {
		return $out;
	}
	$url = wp_get_attachment_url( $id );
	if ( ! $url ) {
		return $out;
	}
	$meta = wp_get_attachment_metadata( $id );
	$w    = isset( $meta['width'] ) ? (int) $meta['width'] : 0;
	$h    = isset( $meta['height'] ) ? (int) $meta['height'] : 0;
	return array( $url, $w, $h, false );
}
add_filter( 'image_downsize', 'nasly_keep_gif_animation', 10, 2 );

/**
 * Favicon Camm: pisa cualquier icono por defecto (p. ej. la W de WordPress).
 */
function nasly_head_meta(): void {
	$uri = get_template_directory_uri() . '/assets/img';
	$ver = NASLY_VERSION;
	// Quitar posibles iconos previos del buffer no es viable; imprimimos los nuestros primero y al final.
	echo '<link rel="icon" href="' . esc_url( home_url( '/favicon.ico?v=' . $ver ) ) . '" sizes="any">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url( $uri . '/camm-icon-16.png?v=' . $ver ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url( $uri . '/camm-icon-32.png?v=' . $ver ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="48x48" href="' . esc_url( $uri . '/camm-icon-48.png?v=' . $ver ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url( $uri . '/camm-icon-512.png?v=' . $ver ) . '">' . "\n";
	echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url( $uri . '/camm-icon-180.png?v=' . $ver ) . '">' . "\n";
	echo '<meta name="theme-color" content="#060409">' . "\n";
}
add_action( 'wp_head', 'nasly_head_meta', 1 );

/**
 * Fuerza la URL del site icon de WordPress al de Camm.
 *
 * @param string $url     URL.
 * @param int    $size    Tamaño.
 * @param int    $blog_id Blog.
 * @return string
 */
function nasly_force_site_icon_url( $url, $size = 512, $blog_id = 0 ): string {
	$file = (int) $size <= 48 ? 'camm-icon-32.png' : 'camm-icon-512.png';
	return get_template_directory_uri() . '/assets/img/' . $file . '?v=' . NASLY_VERSION;
}
add_filter( 'get_site_icon_url', 'nasly_force_site_icon_url', 99, 3 );

/**
 * Al final del head, vuelve a declarar el favicon Camm por encima de wp_site_icon.
 */
function nasly_head_meta_last(): void {
	$uri = get_template_directory_uri() . '/assets/img';
	$ver = NASLY_VERSION;
	echo '<link rel="shortcut icon" href="' . esc_url( $uri . '/camm-icon-32.png?v=' . $ver ) . '">' . "\n";
}
add_action( 'wp_head', 'nasly_head_meta_last', 100 );

/**
 * CPT Proyecto + taxonomía Disciplina.
 */
function nasly_register_content_types(): void {
	register_post_type(
		'proyecto',
		array(
			'labels'              => array(
				'name'          => 'Proyectos',
				'singular_name' => 'Proyecto',
				'add_new_item'  => 'Añadir proyecto',
				'edit_item'     => 'Editar proyecto',
				'view_item'     => 'Ver proyecto',
				'search_items'  => 'Buscar proyectos',
				'not_found'     => 'No hay proyectos',
			),
			'public'              => true,
			'has_archive'         => false,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-portfolio',
			'menu_position'       => 5,
			'rewrite'             => array(
				'slug'       => 'portafolio',
				'with_front' => false,
			),
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
			'taxonomies'          => array( 'disciplina' ),
			'exclude_from_search' => false,
		)
	);

	register_taxonomy(
		'disciplina',
		array( 'proyecto' ),
		array(
			'labels'            => array(
				'name'          => 'Disciplinas',
				'singular_name' => 'Disciplina',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'         => 'disciplina',
				'with_front'   => false,
				'hierarchical' => true,
			),
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'nasly_register_content_types' );

/**
 * Lee un SVG del tema y limpia atributos peligrosos.
 */
function nasly_get_svg( string $filename ): string {
	$path = get_template_directory() . '/assets/img/' . $filename;
	if ( ! is_readable( $path ) ) {
		return '';
	}
	$svg = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( false === $svg ) {
		return '';
	}
	$svg = preg_replace( '/<\?xml[^>]*>/', '', $svg );
	return is_string( $svg ) ? $svg : '';
}

/**
 * Marca tipográfica Camm en cabecera y pie.
 *
 * @param string $block_content Contenido renderizado.
 * @param array  $block         Bloque.
 */
function nasly_render_site_title( string $block_content, array $block ): string {
	$class = $block['attrs']['className'] ?? '';
	if ( false === strpos( $class, 'nasly-logo' ) ) {
		return $block_content;
	}

	$home = esc_url( home_url( '/' ) );
	$name = esc_html( get_bloginfo( 'name' ) );

	if ( false !== strpos( $class, 'nasly-logo-pie' ) ) {
		return sprintf(
			'<div class="wp-block-site-title nasly-logo-pie"><a href="%s" rel="home" class="nasly-wordmark nasly-wordmark--pie" aria-label="%s — inicio"><span class="nasly-wordmark__mark">MACA</span></a></div>',
			$home,
			$name
		);
	}

	return sprintf(
		'<p class="wp-block-site-title nasly-logo"><a href="%s" rel="home" class="nasly-wordmark" aria-label="%s — inicio"><span class="nasly-wordmark__mark">MACA</span><span class="nasly-logo__nombre">Visual Journal</span></a></p>',
		$home,
		$name
	);
}
add_filter( 'render_block_core/site-title', 'nasly_render_site_title', 10, 2 );

/**
 * Clase body auxiliar en la página de archivo del portafolio.
 *
 * @param string[] $classes Clases.
 * @return string[]
 */
function nasly_body_classes( array $classes ): array {
	if ( is_page( 'portafolio' ) ) {
		$classes[] = 'nasly-en-portafolio';
	}
	return $classes;
}
add_filter( 'body_class', 'nasly_body_classes' );

/**
 * Flush rewrite rules al activar el tema.
 */
function nasly_after_switch_theme(): void {
	nasly_register_content_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'nasly_after_switch_theme' );
