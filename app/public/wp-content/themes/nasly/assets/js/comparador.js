/**
 * Comparador antes / después.
 *
 * Toma cualquier grupo con la clase "nasly-comparador" que tenga dos imágenes
 * y lo convierte en un corte que se arrastra. La primera imagen es el "antes".
 * Funciona con mouse, dedo y teclado (flechas, Inicio, Fin).
 */
( function () {
	'use strict';

	var FLECHAS =
		'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 6 3.5 12l6 6 1.4-1.4L6.3 12l4.6-4.6zM14.5 6l-1.4 1.4 4.6 4.6-4.6 4.6 1.4 1.4 6-6z"/></svg>';

	function preparar( caja ) {
		var figuras = caja.querySelectorAll( ':scope > figure.wp-block-image' );
		if ( figuras.length < 2 || caja.classList.contains( 'is-activo' ) ) {
			return;
		}
		var antes = figuras[ 0 ].querySelector( 'img' );
		var despues = figuras[ 1 ].querySelector( 'img' );
		if ( ! antes || ! despues ) {
			return;
		}

		function ajustarProporcion() {
			var img = despues.naturalWidth ? despues : antes;
			if ( img.naturalWidth && img.naturalHeight ) {
				caja.style.setProperty( '--nasly-ratio', img.naturalWidth + ' / ' + img.naturalHeight );
			}
		}
		ajustarProporcion();
		despues.addEventListener( 'load', ajustarProporcion );
		antes.addEventListener( 'load', ajustarProporcion );

		var manija = document.createElement( 'div' );
		manija.className = 'nasly-comparador__manija';
		manija.innerHTML = '<span class="nasly-comparador__perilla">' + FLECHAS + '</span>';
		caja.appendChild( manija );

		var etiquetaAntes = figuras[ 0 ].querySelector( 'figcaption' );
		var etiquetaDespues = figuras[ 1 ].querySelector( 'figcaption' );
		caja.setAttribute( 'role', 'slider' );
		caja.setAttribute( 'tabindex', '0' );
		caja.setAttribute( 'aria-valuemin', '0' );
		caja.setAttribute( 'aria-valuemax', '100' );
		caja.setAttribute(
			'aria-label',
			'Comparar: ' +
				( etiquetaAntes ? etiquetaAntes.textContent : 'antes' ) +
				' y ' +
				( etiquetaDespues ? etiquetaDespues.textContent : 'después' )
		);
		caja.classList.add( 'is-activo' );

		var valor = 50;
		var arrastrando = false;

		function fijar( p ) {
			valor = Math.max( 0, Math.min( 100, p ) );
			caja.style.setProperty( '--nasly-corte', valor + '%' );
			caja.setAttribute( 'aria-valuenow', Math.round( valor ) );
			caja.setAttribute( 'aria-valuetext', Math.round( valor ) + ' por ciento' );
		}

		function desdeEvento( e ) {
			var r = caja.getBoundingClientRect();
			fijar( ( ( e.clientX - r.left ) / r.width ) * 100 );
		}

		caja.addEventListener( 'pointerdown', function ( e ) {
			arrastrando = true;
			caja.setPointerCapture( e.pointerId );
			desdeEvento( e );
		} );
		caja.addEventListener( 'pointermove', function ( e ) {
			if ( arrastrando ) {
				desdeEvento( e );
			}
		} );
		caja.addEventListener( 'pointerup', function () {
			arrastrando = false;
		} );
		caja.addEventListener( 'pointercancel', function () {
			arrastrando = false;
		} );
		caja.addEventListener( 'keydown', function ( e ) {
			var paso = e.shiftKey ? 10 : 2;
			if ( 'ArrowLeft' === e.key ) {
				fijar( valor - paso );
			} else if ( 'ArrowRight' === e.key ) {
				fijar( valor + paso );
			} else if ( 'Home' === e.key ) {
				fijar( 0 );
			} else if ( 'End' === e.key ) {
				fijar( 100 );
			} else {
				return;
			}
			e.preventDefault();
		} );

		fijar( 50 );
	}

	function iniciar() {
		document.querySelectorAll( '.nasly-comparador' ).forEach( preparar );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', iniciar );
	} else {
		iniciar();
	}
} )();
