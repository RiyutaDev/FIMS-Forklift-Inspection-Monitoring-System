import './bootstrap';

// Provide jQuery globally in case some legacy parts expect it
import $ from 'jquery';
window.$ = window.jQuery = $;

// Styles from node_modules
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';

// JS from node_modules
import 'bootstrap';
import 'admin-lte/dist/js/adminlte.min.js';

import * as lucide from 'lucide';

window.lucide = lucide;

document.addEventListener('DOMContentLoaded', () => {
    if (lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
});