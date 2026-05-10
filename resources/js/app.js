import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
window.createIcons = createIcons;
window.icons = icons;

Alpine.plugin(collapse);

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});