require('./bootstrap');
require('./vendor/livewire-editorjs/editorjs.js');

import BigPicture from 'bigpicture';

const Trix = require('trix');
Trix.config.blockAttributes.heading1.tagName = 'h4';
Trix.config.blockAttributes.default.tagName = 'p';

const buttons = document.querySelectorAll('.initGallery');
for (const button of buttons) {
	button.addEventListener('click', function () {
		setTimeout(function () {
			var imageLinks = document.querySelectorAll('#lightbox div img');
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
		}, 1000);
	});
}

window.Alpine.start();
