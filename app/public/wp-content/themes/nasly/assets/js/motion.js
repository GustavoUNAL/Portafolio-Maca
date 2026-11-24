(() => {
	const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	const header = document.querySelector('.nasly-cabecera');
	if (header) {
		const onScroll = () => {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	if (reduce) return;

	const reveal = document.querySelectorAll(
		'.nasly-disciplina, .nasly-tarjeta, .nasly-cta, .nasly-hero > *:not(.nasly-monograma)'
	);
	reveal.forEach((el, i) => {
		el.classList.add('nasly-reveal');
		el.style.setProperty('--nasly-delay', `${Math.min(i * 60, 360)}ms`);
	});

	const io = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-in');
					io.unobserve(entry.target);
				}
			});
		},
		{ threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
	);
	document.querySelectorAll('.nasly-reveal').forEach((el) => io.observe(el));

	document.querySelectorAll('.nasly-tarjeta').forEach((card) => {
		card.addEventListener('pointermove', (e) => {
			const r = card.getBoundingClientRect();
			const x = ((e.clientX - r.left) / r.width) * 100;
			const y = ((e.clientY - r.top) / r.height) * 100;
			card.style.setProperty('--mx', `${x}%`);
			card.style.setProperty('--my', `${y}%`);
		});
	});
})();
