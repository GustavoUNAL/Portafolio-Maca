(() => {
	const stages = document.querySelectorAll('[data-maca-stage3d]');
	if (!stages.length) return;

	stages.forEach((stage) => {
		const ring = stage.querySelector('.maca-stage3d__ring');
		const cards = Array.from(stage.querySelectorAll('.maca-stage3d__card'));
		const prev = stage.querySelector('[data-maca-prev]');
		const next = stage.querySelector('[data-maca-next]');
		const dots = stage.querySelector('.maca-stage3d__dots');
		if (!ring || cards.length < 2) return;

		const total = cards.length;
		const step = 360 / total;
		let index = 0;
		let autoTimer = null;

		cards.forEach((card, i) => {
			card.style.setProperty('--i', String(i));
			card.style.setProperty('--step', `${step}deg`);
		});

		const setActive = (nextIndex) => {
			index = (nextIndex + total) % total;
			ring.style.setProperty('--rot', `${-index * step}deg`);
			cards.forEach((card, i) => {
				const on = i === index;
				card.classList.toggle('is-active', on);
				const video = card.querySelector('video');
				if (!video) return;
				if (on) {
					video.play().catch(() => {});
				} else {
					video.pause();
					try {
						video.currentTime = 0;
					} catch (_) {}
				}
			});
			if (dots) {
				dots.querySelectorAll('button').forEach((btn, i) => {
					const on = i === index;
					btn.classList.toggle('is-active', on);
					if (on) btn.setAttribute('aria-current', 'true');
					else btn.removeAttribute('aria-current');
				});
			}
		};

		if (dots) {
			cards.forEach((_, i) => {
				const btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'maca-stage3d__dot';
				btn.setAttribute('aria-label', `Ver pieza ${i + 1}`);
				btn.addEventListener('click', () => {
					stopAuto();
					setActive(i);
					startAuto();
				});
				dots.appendChild(btn);
			});
		}

		const stopAuto = () => {
			if (autoTimer) {
				clearInterval(autoTimer);
				autoTimer = null;
			}
		};

		const startAuto = () => {
			stopAuto();
			autoTimer = window.setInterval(() => setActive(index + 1), 5200);
		};

		prev?.addEventListener('click', () => {
			stopAuto();
			setActive(index - 1);
			startAuto();
		});
		next?.addEventListener('click', () => {
			stopAuto();
			setActive(index + 1);
			startAuto();
		});

		stage.addEventListener('pointerenter', stopAuto);
		stage.addEventListener('pointerleave', startAuto);

		setActive(0);
		startAuto();
	});
})();
