import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import LabLicenseTable from './components/LabLicenseTable.vue';

window.Alpine = Alpine;
Alpine.start();

// Create Vue application
const app = createApp({});

// Register components globally
app.component('lab-license-table', LabLicenseTable);

// Mount the application
app.mount('#app');

// Add this for debugging
app.config.errorHandler = (err) => {
    console.error('Vue Error:', err);
};
