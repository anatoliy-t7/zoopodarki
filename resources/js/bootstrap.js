import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import TouchSweep from 'touchsweep';
Alpine.plugin(persist);
import ToastComponent from '../../vendor/usernotnull/tall-toasts/dist/js/tall-toasts';
Alpine.data('ToastComponent', ToastComponent);

window.Alpine = Alpine;
window.TouchSweep = TouchSweep;

var lozad = require('lozad');

const observer = lozad(); // lazy loads elements with default selector as '.lozad'
observer.observe();

// after filter products call it again
window.livewire.on('lozad', () => {
	observer.observe();
});
