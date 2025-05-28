import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import LabLicenseTable from './components/LabLicenseTable.vue';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component('lab-license-table', LabLicenseTable);
app.mount('#app');
