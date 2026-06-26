import './bootstrap';
import { createApp } from 'vue';
import OnopayPaymentForm from './components/OnopayPaymentForm.vue';

// Tunggu DOM selesai sebelum mount Vue agar #app element pasti ada
document.addEventListener('DOMContentLoaded', () => {
    const appEl = document.getElementById('app');
    if (appEl) {
        const app = createApp({});
        app.component('onopay-payment-form', OnopayPaymentForm);
        app.mount('#app');
    }
});