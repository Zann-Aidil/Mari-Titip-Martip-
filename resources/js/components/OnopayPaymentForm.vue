<!-- resources/js/components/OnopayPaymentForm.vue -->

<template>
  <div class="onopay-wrapper">
    <!-- Trigger Button -->
    <button @click="startPayment" :disabled="loading" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-200">
      <i class='bx bx-lock-alt' v-if="!loading"></i>
      <span class="animate-spin" v-if="loading">⏳</span>
      {{ loading ? 'Memproses...' : 'Bayar Sekarang' }}
    </button>

    <div v-if="error && step === 1" class="text-red-500 text-sm mt-2 text-center">
      {{ error }}
    </div>

    <!-- Modal Overlay -->
    <div v-if="step > 1" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 relative">
        
        <!-- Close Button (only if not success) -->
        <button v-if="step !== 4" @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
          <i class='bx bx-x text-2xl'></i>
        </button>

        <!-- Step 2 & 3 combined: QR Code & Payment Input -->
        <div v-if="step === 2 || step === 3" class="text-center">
          <h2 class="text-xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h2>
          <p class="text-sm text-gray-500 mb-6">Total: <span class="font-bold text-green-600 text-lg">Rp {{ formatNumber(amount) }}</span></p>
          
          <div class="qr-image flex justify-center mb-6">
            <img :src="qrData.qr_image" alt="QR Code" class="w-48 h-48 border border-gray-200 rounded-xl p-2" />
          </div>

          <form @submit.prevent="processPayment" class="text-left bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Anda (Pembayar)</label>
              <input 
                v-model="paymentForm.payer_phone" 
                type="text" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                placeholder="Misal: 08123456789"
                required
              />
            </div>

            <button type="submit" :disabled="loading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
              {{ loading ? 'Memproses Transaksi...' : 'Konfirmasi & Bayar' }}
            </button>
          </form>

          <div v-if="error" class="mt-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100">
            {{ error }}
          </div>
        </div>

        <!-- Step 4: Success / Receipt -->
        <div v-if="step === 4" class="step-success printable-receipt">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
              <i class='bx bx-check text-4xl'></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Pembayaran Berhasil</h2>
            <p class="text-gray-500 text-sm">Terima kasih telah menggunakan E-Wallet OnoPay</p>
          </div>
          
          <div class="transaction-details bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm space-y-2">
            <h3 class="font-bold mb-3 border-b pb-2 text-center text-gray-800">STRUK TRANSAKSI</h3>
            <div class="flex justify-between"><span class="text-gray-500">ID Transaksi</span> <span class="font-medium">{{ paymentResult.transaction_id }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">ID Titipan</span> <span class="font-medium">{{ trackId }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Jumlah</span> <span class="font-bold text-green-600">Rp {{ formatNumber(paymentResult.amount) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Pembayar</span> <span class="font-medium">{{ paymentResult.payer_phone }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Waktu</span> <span class="font-medium">{{ new Date().toLocaleString('id-ID') }}</span></div>
          </div>

          <div class="mt-8 flex gap-3 no-print">
            <button @click="printReceipt" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2">
              <i class='bx bx-printer'></i> Cetak Struk
            </button>
            <button @click="finish" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
              Selesai
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { OnopayService } from '../services/onopay.js';

export default {
  name: 'OnopayPaymentForm',
  props: {
    amount: {
      type: Number,
      required: true
    },
    trackId: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      step: 1, // 1: Button, 2/3: Modal QR & Pay, 4: Success
      loading: false,
      error: null,
      systemReceiverPhone: '08123456789', // Dummy system receiver
      paymentForm: {
        payer_phone: ''
      },
      qrData: {},
      paymentResult: {}
    };
  },
  methods: {
    async startPayment() {
      this.loading = true;
      this.error = null;

      // Automatically generate QR for the predefined amount and trackId
      const description = `Pembayaran untuk ID ${this.trackId}`;
      const result = await OnopayService.generateQR(
        this.systemReceiverPhone,
        this.amount,
        'MARTIP',
        description
      );

      if (result.success) {
        this.qrData = result.data;
        this.step = 2; // Show Modal
      } else {
        this.error = result.message || 'Gagal menyiapkan pembayaran OnoPay.';
      }

      this.loading = false;
    },

    async processPayment() {
      this.loading = true;
      this.error = null;

      const result = await OnopayService.pay(
        this.qrData.qr_code,
        this.paymentForm.payer_phone
      );

      if (result.success) {
        this.paymentResult = result.data;
        this.step = 4;
      } else {
        this.error = result.message || 'Pembayaran ditolak atau gagal.';
      }

      this.loading = false;
    },

    closeModal() {
      this.step = 1;
      this.error = null;
    },

    finish() {
      // Redirect or reload page after finish
      window.location.href = '/user/riwayat'; // Example redirection
    },

    printReceipt() {
      const receiptElement = document.querySelector('.printable-receipt');
      if (!receiptElement) return;

      const receiptHTML = receiptElement.innerHTML;
      const printWindow = window.open('', '_blank', 'height=600,width=800');
      
      // Ambil style dari halaman utama agar struk tetap rapi
      let stylesHtml = '';
      document.querySelectorAll('link[rel="stylesheet"], style').forEach(node => {
        stylesHtml += node.outerHTML;
      });
      
      printWindow.document.write(`
        <html>
          <head>
            <title>Struk Pembayaran MARTIP</title>
            ${stylesHtml}
            <style>
              body { background: white !important; padding: 40px; color: black; }
              .no-print { display: none !important; }
            </style>
          </head>
          <body>
            <div class="max-w-md mx-auto border border-gray-200 rounded-2xl p-8 shadow-sm">
              ${receiptHTML}
            </div>
            <script>
              window.onload = function() {
                setTimeout(function() {
                  window.print();
                  window.close();
                }, 500);
              }
            <\/script>
          </body>
        </html>
      `);
      printWindow.document.close();
    },

    formatNumber(num) {
      if (!num) return '0';
      return new Intl.NumberFormat('id-ID').format(num);
    }
  }
};
</script>

<style scoped>
/* Removed buggy print media queries, printing is handled via JS new window now */
</style>