import './bootstrap';

// ⬇️ GANTI KODE CHART.JS LAMA (YANG ERROR) DENGAN INI ⬇️
import { Chart, registerables } from 'chart.js';
Chart.register(registerables);
// ⬆️ BATAS PERBAIKAN ⬆️

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();