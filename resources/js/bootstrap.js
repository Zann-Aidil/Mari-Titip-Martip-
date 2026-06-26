import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Ambil CSRF token dari meta tag Laravel (wajib agar request POST berjalan di hosting/production)
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
}

// Pastikan axios selalu kirim cookie (untuk session auth di hosting)
window.axios.defaults.withCredentials = true;
