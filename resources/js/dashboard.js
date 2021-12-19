require('./bootstrap');

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

import suneditor from 'suneditor';
import plugins from 'suneditor/src/plugins';
import lang from 'suneditor/src/lang';

const Editor = suneditor.init({
	plugins: plugins,
	buttonList: [
		['undo', 'redo'],
		['formatBlock'],
		['bold', 'italic', 'outdent', 'indent', 'align', 'list'],
		['table', 'link', 'image', 'video'],
		['fullScreen', 'showBlocks', 'codeView'],
		['removeFormat'],
	],
	height: 'auto',
	lang: lang.ru,
});

window.Editor = Editor;

window.Alpine.start();
