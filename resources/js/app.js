import './bootstrap';
import { createApp } from 'vue';
import OnopayPaymentForm from './components/OnopayPaymentForm.vue';

const app = createApp({});

app.component('onopay-payment-form', OnopayPaymentForm);

app.mount('#app');