<?php
/**
 * Sincroniza las entradas del Visual Journal como posts reales de WordPress.
 * Aparecen en Entradas → Todos los posts del admin.
 *
 * @package Nasly
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Importa un archivo del tema a la biblioteca de medios (idempotente).
 *
 * @return int Attachment ID o 0.
 */
function maca_sideload_theme_asset( string $filename, int $parent_post_id = 0 ): int {
	$filename = basename( $filename );
	$src      = get_template_directory() . '/assets/maca/' . $filename;
	if ( ! is_readable( $src ) ) {
		return 0;
	}

	// Reutilizar si ya existe un attachment con este nombre.
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'name'           => sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	// Buscar por meta del archivo original.
	$q = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_maca_source_file',
					'value' => $filename,
				),
			),
		)
	);
	if ( $q->posts ) {
		return (int) $q->posts[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['error'] ) ) {
		return 0;
	}

	$dest = trailingslashit( $upload_dir['path'] ) . wp_unique_filename( $upload_dir['path'], $filename );
	if ( ! copy( $src, $dest ) ) {
		return 0;
	}

	$filetype = wp_check_filetype( $dest, null );
	$attach_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$dest,
		$parent_post_id
	);

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return 0;
	}

	$meta = wp_generate_attachment_metadata( $attach_id, $dest );
	if ( $meta ) {
		wp_update_attachment_metadata( $attach_id, $meta );
	}
	update_post_meta( $attach_id, '_maca_source_file', $filename );

	return (int) $attach_id;
}

/**
 * Construye el HTML de bloques de una entrada usando IDs de media.
 *
 * @param array<string, mixed> $entry Entrada.
 * @param array<string, int>   $media Mapa file => attachment ID.
 */
function maca_build_post_content( array $entry, array $media ): string {
	$lede = esc_html( $entry['lede'] );
	$html = "<!-- wp:paragraph {\"className\":\"maca-lede\"} -->\n<p class=\"maca-lede\">{$lede}</p>\n<!-- /wp:paragraph -->\n\n";
	$html .= "<!-- wp:separator {\"className\":\"maca-rule\"} -->\n<hr class=\"wp-block-separator has-alpha-channel-opacity maca-rule\"/>\n<!-- /wp:separator -->\n\n";

	$type = $entry['body'];

	if ( 'perfil' === $type ) {
		$img  = $entry['images'][0];
		$id   = $media[ $img['file'] ] ?? 0;
		$url  = $id ? wp_get_attachment_image_url( $id, 'large' ) : maca_asset( $img['file'] );
		$alt  = esc_attr( $img['alt'] );
		$cap  = esc_html( $img['cap'] );
		$html .= "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"maca-perfil\"} -->\n";
		$html .= "<figure class=\"wp-block-image size-large maca-perfil\"><img src=\"" . esc_url( $url ) . "\" alt=\"{$alt}\" class=\"wp-image-{$id}\"/><figcaption class=\"maca-caption\">{$cap}</figcaption></figure>\n";
		$html .= "<!-- /wp:image -->\n";
		return $html;
	}

	if ( 'illu3d' === $type ) {
		$v = array(
			array( 'video' => 'maca-3d-criatura.mp4', 'poster' => 'maca-3d-criatura-poster.jpg', 'label' => 'Criatura' ),
			array( 'video' => 'maca-3d-personaje.mp4', 'poster' => 'maca-3d-personaje-poster.jpg', 'label' => 'Personaje' ),
			array( 'video' => 'maca-3d-figura.mp4', 'poster' => 'maca-3d-figura-poster.jpg', 'label' => 'Figura' ),
		);
		$cards = '';
		foreach ( $v as $it ) {
			$vid_id = $media[ $it['video'] ] ?? 0;
			$pos_id = $media[ $it['poster'] ] ?? 0;
			$vid    = $vid_id ? wp_get_attachment_url( $vid_id ) : maca_asset( $it['video'] );
			$pos    = $pos_id ? wp_get_attachment_url( $pos_id ) : maca_asset( $it['poster'] );
			$cards .= '<figure class="maca-stage3d__card"><video src="' . esc_url( $vid ) . '" poster="' . esc_url( $pos ) . '" muted playsinline loop preload="metadata" aria-label="' . esc_attr( $it['label'] ) . ' 3D Maca"></video><figcaption>' . esc_html( $it['label'] ) . '</figcaption></figure>';
		}
		$html .= "<!-- wp:html -->\n<div class=\"maca-stage3d\" data-maca-stage3d>\n  <p class=\"maca-stage3d__label\">Maca · Escultura 3D</p>\n  <div class=\"maca-stage3d__scene\">\n    <div class=\"maca-stage3d__floor\" aria-hidden=\"true\"></div>\n    <div class=\"maca-stage3d__ring\">{$cards}</div>\n  </div>\n  <div class=\"maca-stage3d__controls\">\n    <button type=\"button\" class=\"maca-stage3d__btn\" data-maca-prev>Anterior</button>\n    <div class=\"maca-stage3d__dots\" aria-label=\"Piezas 3D\"></div>\n    <button type=\"button\" class=\"maca-stage3d__btn\" data-maca-next>Siguiente</button>\n  </div>\n</div>\n<!-- /wp:html -->\n\n";
		$html .= "<!-- wp:separator {\"className\":\"maca-rule\"} -->\n<hr class=\"wp-block-separator has-alpha-channel-opacity maca-rule\"/>\n<!-- /wp:separator -->\n\n";
		$html .= "<!-- wp:paragraph {\"className\":\"maca-stage3d__label\"} -->\n<p class=\"maca-stage3d__label\">Ilustración digital</p>\n<!-- /wp:paragraph -->\n\n";
	}

	if ( 'collage' === $type ) {
		$html .= "<!-- wp:group {\"className\":\"maca-collage-gallery\",\"layout\":{\"type\":\"default\"}} -->\n<div class=\"wp-block-group maca-collage-gallery\">\n";
		foreach ( $entry['images'] as $img ) {
			$id  = $media[ $img['file'] ] ?? 0;
			$url = $id ? wp_get_attachment_image_url( $id, 'large' ) : maca_asset( $img['file'] );
			$alt = esc_attr( $img['alt'] );
			$cap = esc_html( $img['cap'] ?? '' );
			$cap_html = $cap !== '' ? "<figcaption class=\"maca-caption\">{$cap}</figcaption>" : '';
			$html .= "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"maca-collage-gallery__item\"} -->\n";
			$html .= "<figure class=\"wp-block-image size-large maca-collage-gallery__item\"><img src=\"" . esc_url( $url ) . "\" alt=\"{$alt}\" class=\"wp-image-{$id}\"/>{$cap_html}</figure>\n";
			$html .= "<!-- /wp:image -->\n\n";
		}
		$html .= "</div>\n<!-- /wp:group -->\n";
		return $html;
	}

	// duo / illu3d images
	$layout = esc_attr( $entry['layout'] );
	$html  .= "<!-- wp:group {\"className\":\"{$layout}\",\"layout\":{\"type\":\"default\"}} -->\n<div class=\"wp-block-group {$layout}\">\n";
	foreach ( $entry['images'] as $img ) {
		$id    = $media[ $img['file'] ] ?? 0;
		$url   = $id ? wp_get_attachment_image_url( $id, 'large' ) : maca_asset( $img['file'] );
		$alt   = esc_attr( $img['alt'] );
		$cap   = esc_html( $img['cap'] );
		$class = esc_attr( $img['class'] ?? '' );
		$html .= "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"{$class}\"} -->\n";
		$html .= "<figure class=\"wp-block-image size-large {$class}\"><img src=\"" . esc_url( $url ) . "\" alt=\"{$alt}\" class=\"wp-image-{$id}\"/><figcaption class=\"maca-caption\">{$cap}</figcaption></figure>\n";
		$html .= "<!-- /wp:image -->\n\n";
	}
	$html .= "</div>\n<!-- /wp:group -->\n";

	return $html;
}

/**
 * Asegura el usuario justcamm777 (dueño del portafolio).
 * La marca visual sigue siendo MACA; solo cambia el autor WP.
 *
 * @return int User ID.
 */
function maca_ensure_justcamm_user( string $password = '' ): int {
	$login = 'justcamm777';
	$user  = get_user_by( 'login', $login );

	if ( ! $user ) {
		$pass = $password !== '' ? $password : wp_generate_password( 16, true );
		$uid  = wp_create_user( $login, $pass, 'hello@justcamm.online' );
		if ( is_wp_error( $uid ) ) {
			// Fallback: primer administrador.
			$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
			return $admins ? (int) $admins[0] : 1;
		}
		$user = new WP_User( (int) $uid );
		$user->set_role( 'administrator' );
	} else {
		$uid = (int) $user->ID;
		if ( $password !== '' ) {
			wp_set_password( $password, $uid );
		}
	}

	wp_update_user(
		array(
			'ID'           => (int) $user->ID,
			'display_name' => 'justcamm',
			'nickname'     => 'justcamm',
			'first_name'   => 'justcamm',
		)
	);
	$user->set_role( 'administrator' );

	return (int) $user->ID;
}

/**
 * Asegura categoría Visual Journal.
 */
function maca_ensure_journal_category(): int {
	$term = term_exists( 'Visual Journal', 'category' );
	if ( ! $term ) {
		$term = wp_insert_term(
			'Visual Journal',
			'category',
			array(
				'slug'        => 'visual-journal',
				'description' => 'Entradas del Visual Journal MACA',
			)
		);
	}
	if ( is_wp_error( $term ) ) {
		return 0;
	}
	return (int) ( is_array( $term ) ? $term['term_id'] : $term );
}

/**
 * Crea / actualiza las 6 entradas en WordPress (autor: justcamm777).
 *
 * @param string $password Clave opcional para justcamm777.
 * @return array{created:int,updated:int,media:int,author:string,errors:string[]}
 */
function maca_seed_journal( string $password = '' ): array {
	$result = array(
		'created' => 0,
		'updated' => 0,
		'media'   => 0,
		'author'  => 'justcamm777',
		'errors'  => array(),
	);

	$author_id = maca_ensure_justcamm_user( $password );
	$cat_id    = maca_ensure_journal_category();

	// Marca del sitio (visual MACA, dueño justcamm).
	update_option( 'blogname', 'MACA' );
	update_option( 'blogdescription', 'Visual Journal' );
	update_option( 'show_on_front', 'posts' );

	// Borrar "Hello world!" si existe.
	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello instanceof WP_Post ) {
		wp_delete_post( $hello->ID, true );
	}

	$order = 0;
	foreach ( maca_journal_entries() as $entry ) {
		++$order;
		$files = array( $entry['thumb'] );
		foreach ( $entry['images'] as $img ) {
			$files[] = $img['file'];
		}
		if ( 'illu3d' === $entry['body'] ) {
			$files = array_merge(
				$files,
				array(
					'maca-3d-criatura.mp4',
					'maca-3d-criatura-poster.jpg',
					'maca-3d-personaje.mp4',
					'maca-3d-personaje-poster.jpg',
					'maca-3d-figura.mp4',
					'maca-3d-figura-poster.jpg',
				)
			);
		}
		$files = array_unique( $files );

		$media_map = array();
		foreach ( $files as $file ) {
			$id = maca_sideload_theme_asset( $file, 0 );
			if ( $id ) {
				$media_map[ $file ] = $id;
				wp_update_post(
					array(
						'ID'          => $id,
						'post_author' => $author_id,
					)
				);
				++$result['media'];
			} else {
				$result['errors'][] = "No se pudo importar: {$file}";
			}
		}

		$content = maca_build_post_content( $entry, $media_map );
		$existing = get_page_by_path( $entry['slug'], OBJECT, 'post' );

		$postarr = array(
			'post_title'   => $entry['title'],
			'post_name'    => $entry['slug'],
			'post_content' => $content,
			'post_excerpt' => $entry['excerpt'],
			'post_status'  => 'publish',
			'post_type'    => 'post',
			'menu_order'   => $order,
			'post_author'  => $author_id,
		);

		if ( $existing instanceof WP_Post ) {
			$postarr['ID'] = $existing->ID;
			$post_id       = wp_update_post( $postarr, true );
			if ( is_wp_error( $post_id ) ) {
				$result['errors'][] = $post_id->get_error_message();
				continue;
			}
			++$result['updated'];
		} else {
			$post_id = wp_insert_post( $postarr, true );
			if ( is_wp_error( $post_id ) ) {
				$result['errors'][] = $post_id->get_error_message();
				continue;
			}
			++$result['created'];
		}

		$post_id = (int) $post_id;
		if ( $cat_id ) {
			wp_set_post_categories( $post_id, array( $cat_id ) );
		}
		update_post_meta( $post_id, '_maca_journal_num', $entry['num'] );
		update_post_meta( $post_id, '_maca_journal_slug', $entry['slug'] );

		$thumb_id = $media_map[ $entry['thumb'] ] ?? 0;
		if ( $thumb_id ) {
			set_post_thumbnail( $post_id, $thumb_id );
		}
	}

	// Página Visual Journal si no existe.
	$page = get_page_by_path( 'visual-journal' );
	if ( ! $page ) {
		wp_insert_post(
			array(
				'post_title'   => 'Visual Journal',
				'post_name'    => 'visual-journal',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '<!-- wp:shortcode -->[maca_journal]<!-- /wp:shortcode -->',
			)
		);
	}

	flush_rewrite_rules( false );
	update_option( 'maca_journal_seeded', NASLY_VERSION );

	return $result;
}

/**
 * Menú admin: Apariencia → Sincronizar Visual Journal.
 */
function maca_register_seed_admin(): void {
	add_theme_page(
		'Sincronizar Visual Journal',
		'Visual Journal MACA',
		'edit_posts',
		'maca-seed-journal',
		'maca_render_seed_admin'
	);
}
add_action( 'admin_menu', 'maca_register_seed_admin' );

/**
 * Pantalla admin.
 */
function maca_render_seed_admin(): void {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$notice = '';
	if ( isset( $_POST['maca_seed_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maca_seed_nonce'] ) ), 'maca_seed' ) ) {
		$r      = maca_seed_journal();
		$notice = sprintf(
			'Listo: %d creadas, %d actualizadas, %d archivos de media. Errores: %d',
			$r['created'],
			$r['updated'],
			$r['media'],
			count( $r['errors'] )
		);
		if ( $r['errors'] ) {
			$notice .= '<br>' . esc_html( implode( ' · ', $r['errors'] ) );
		}
	}

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 20,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'category_name'  => 'visual-journal',
		)
	);
	?>
	<div class="wrap">
		<h1>Visual Journal MACA</h1>
		<?php if ( $notice ) : ?>
			<div class="notice notice-success"><p><?php echo wp_kses_post( $notice ); ?></p></div>
		<?php endif; ?>
		<p>Crea o actualiza las 6 entradas del Visual Journal como <strong>posts de WordPress</strong> (con imágenes en Medios).</p>
		<form method="post">
			<?php wp_nonce_field( 'maca_seed', 'maca_seed_nonce' ); ?>
			<p><button type="submit" class="button button-primary">Sincronizar entradas ahora</button></p>
		</form>
		<hr>
		<h2>Entradas registradas</h2>
		<ul>
			<?php foreach ( $posts as $p ) : ?>
				<li>
					<strong><?php echo esc_html( $p->post_title ); ?></strong>
					— <a href="<?php echo esc_url( get_edit_post_link( $p->ID ) ); ?>">Editar</a>
					· <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">Ver</a>
				</li>
			<?php endforeach; ?>
			<?php if ( ! $posts ) : ?>
				<li>Aún no hay entradas. Pulsa sincronizar.</li>
			<?php endif; ?>
		</ul>
		<p>También en <a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">Entradas → Todos los posts</a>.</p>
	</div>
	<?php
}

/**
 * Auto-seed una vez tras actualizar el tema (si aún no hay posts del journal).
 */
function maca_maybe_auto_seed(): void {
	if ( get_option( 'maca_journal_seeded' ) === NASLY_VERSION ) {
		return;
	}
	$existing = get_posts(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_maca_journal_slug', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		)
	);
	if ( $existing ) {
		update_option( 'maca_journal_seeded', NASLY_VERSION );
		return;
	}
	maca_seed_journal();
}
add_action( 'admin_init', 'maca_maybe_auto_seed' );

/**
 * WP-CLI: wp maca seed
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'maca seed',
		static function ( $args, $assoc_args ) {
			$pass = isset( $assoc_args['password'] ) ? (string) $assoc_args['password'] : '';
			$r    = maca_seed_journal( $pass );
			WP_CLI::success(
				sprintf(
					'Autor %s · Creadas %d · Actualizadas %d · Media %d',
					$r['author'],
					$r['created'],
					$r['updated'],
					$r['media']
				)
			);
			foreach ( $r['errors'] as $err ) {
				WP_CLI::warning( $err );
			}
		},
		array(
			'synopsis' => array(
				array(
					'type'        => 'assoc',
					'name'        => 'password',
					'optional'    => true,
					'description' => 'Contraseña para justcamm777',
				),
			),
		)
	);
}
