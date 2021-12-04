require('./bootstrap');
import BigPicture from 'bigpicture';

// product lightbox
setTimeout(function () {
	if (document.getElementById('lightbox')) {
		var imageLinks = document.querySelectorAll('#lightbox li');
		for (var i = 0; i < imageLinks.length; i++) {
			imageLinks[i].addEventListener('click', function (e) {
				e.preventDefault();
				BigPicture({
					el: e.target,
					gallery: '#lightbox',
					loop: true,
				});
			});
		}
	}
}, 1000);

// reviews lightbox
setTimeout(function () {
	if (document.getElementById('customersGalleryLightbox')) {
		var imageLinks = document.querySelectorAll('#customersGalleryLightbox li');
		for (var i = 0; i < imageLinks.length; i++) {
			imageLinks[i].addEventListener('click', function (e) {
				e.preventDefault();
				BigPicture({
					el: e.target,
					gallery: '#customersGalleryLightbox',
					loop: true,
				});
			});
		}
	}
}, 1000);

setTimeout(function () {
	if (document.querySelectorAll('review')) {
		var imageLinks = document.querySelectorAll('.review div');
		for (var i = 0; i < imageLinks.length; i++) {
			imageLinks[i].addEventListener('click', function (e) {
				e.preventDefault();
				BigPicture({
					el: e.target,
					gallery: '.review',
					loop: true,
				});
			});
		}
	}
}, 1000);

const body = document.body;

if (document.getElementById('megaMenu')) {
	const megaMenu = document.getElementById('megaMenu');
	let lastScroll = 0;

	window.addEventListener('scroll', () => {
		const currentScroll = window.pageYOffset;
		if (currentScroll <= 0) {
			body.classList.remove('scroll-up');
			megaMenu.classList.add('mt-2');
			return;
		}

		if (currentScroll > lastScroll && !body.classList.contains('scroll-down')) {
			// down
			body.classList.remove('scroll-up');
			body.classList.add('scroll-down');
			megaMenu.classList.remove('mt-2');
		} else if (
			currentScroll < lastScroll &&
			body.classList.contains('scroll-down')
		) {
			// up
			body.classList.remove('scroll-down');
			body.classList.add('scroll-up');
			megaMenu.classList.remove('mt-2');
		}
		lastScroll = currentScroll;
	});
}

if (document.getElementById('mobmenu')) {
	let lastScroll = 0;

	window.addEventListener('scroll', () => {
		const currentScroll = window.pageYOffset;
		if (currentScroll <= 0) {
			body.classList.remove('scroll-up');
			return;
		}

		if (currentScroll > lastScroll && !body.classList.contains('scroll-down')) {
			// down
			body.classList.remove('scroll-up');
			body.classList.add('scroll-down');
		} else if (
			currentScroll < lastScroll &&
			body.classList.contains('scroll-down')
		) {
			// up
			body.classList.remove('scroll-down');
			body.classList.add('scroll-up');
		}
		lastScroll = currentScroll;
	});
}

window.Alpine.start();
