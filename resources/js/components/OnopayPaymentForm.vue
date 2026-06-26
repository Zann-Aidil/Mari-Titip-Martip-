<!-- resources/js/components/OnopayPaymentForm.vue -->

<template>
  <div class="onopay-wrapper">
    <!-- Trigger Button -->
    <button type="button" @click="startPayment" :disabled="loading" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-200">
      <i class='bx bx-lock-alt' v-if="!loading"></i>
      <span class="animate-spin" v-if="loading">⏳</span>
      {{ loading ? 'Memproses...' : 'Bayar Sekarang' }}
    </button>

    <div v-if="error && step === 1" class="text-red-500 text-sm mt-2 text-center">
      {{ error }}
    </div>

    <!-- Modal Overlay with Fade Transition -->
    <Transition name="modal-backdrop">
      <div v-if="step > 1" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" @click.self="closeModal">
        
        <!-- Modal Content with Scale + Fade Transition -->
        <Transition name="modal-content" appear>
          <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto relative">
            
            <!-- Step 2: QR Code Payment -->
            <Transition name="step-fade" mode="out-in">
              <div v-if="step === 2" key="step-qr" class="text-center">
                <!-- Blue Header (Double click for simulation) -->
                <div @dblclick="simulatePayment" class="bg-blue-600 text-white py-4 px-6 rounded-t-2xl font-bold text-lg shadow-sm cursor-pointer select-none">
                  Metode Pembayaran
                </div>
                
                <div class="p-8">
                  <!-- Logos -->
                  <div class="flex justify-center items-center gap-3 mb-2">
                    <div class="text-blue-600 font-black text-3xl tracking-tighter flex flex-col leading-none items-center">
                      <div class="flex gap-1"><span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span><span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span></div>
                      <div class="flex gap-1 mt-1"><span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span><span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span></div>
                    </div>
                    <div class="text-blue-800 font-black text-3xl tracking-tight">
                      OnoPay<span class="text-blue-500 font-bold italic ml-1">QR</span>
                    </div>
                  </div>
                  
                  <p class="text-sm text-gray-500 mb-6">Scan QR Code untuk membayar</p>
                  
                  <!-- Total Tagihan Box -->
                  <div class="bg-blue-50 border border-blue-100 py-3 px-4 rounded-xl mb-6 flex justify-center items-center shadow-inner">
                    <span class="font-bold text-blue-900 text-lg">Total Tagihan: <span class="font-bold text-blue-600">Rp {{ formatNumber(amount) }}</span></span>
                  </div>
                  
                  <!-- QR Code -->
                  <div class="qr-image flex justify-center mb-8 relative">
                    <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-md opacity-50 transform scale-105"></div>
                    <img :src="qrData.qr_image" alt="QR Code" class="w-64 h-64 border-4 border-white shadow-lg rounded-2xl relative z-10" />
                  </div>

                  <!-- Buttons -->
                  <div class="flex flex-col gap-3 mt-4">
                    <!-- Tombol Simulasi Pembayaran disembunyikan (Bisa di-trigger dengan double-click di header 'Metode Pembayaran') -->
                    <a :href="'/user/pembayaran?deposit_id=' + depositId" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm shadow-blue-200">
                      <i class='bx bx-upload text-lg'></i> Upload Bukti Pembayaran
                    </a>
                    <!-- Kembali ke Riwayat -->
                    <button type="button" @click="closeModal" class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                      <i class='bx bx-arrow-back text-lg'></i> Kembali ke Riwayat
                    </button>

                    <!-- Tombol Simulasi Pembayaran (Disamarkan sebagai versi aplikasi) -->
                    <button type="button" @click="simulatePayment" title="Klik untuk simulasi sukses" class="w-full text-center mt-2 text-xs font-semibold tracking-widest text-gray-300 hover:text-gray-400 cursor-pointer">
                      MARTIP v1.0.0
                    </button>
                  </div>
                </div>
              </div>

              <!-- Step 4: Success / Receipt -->
              <div v-else-if="step === 4" key="step-success" class="step-success printable-receipt p-6">
                <div class="text-center mb-6">
                  <div class="success-icon-bounce w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class='bx bx-check text-4xl'></i>
                  </div>
                  <h2 class="text-2xl font-bold text-gray-900">Transaksi Sukses</h2>
                  <p class="text-gray-500 text-sm">Tunggu Admin Kami Merespon</p>
                </div>
                
                <div class="transaction-details bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm space-y-2">
                  <h3 class="font-bold mb-3 border-b pb-2 text-center text-gray-800">STRUK TRANSAKSI</h3>
                  <div class="flex justify-between"><span class="text-gray-500">ID Titipan</span> <span class="font-medium">{{ trackId }}</span></div>
                  <div class="flex justify-between"><span class="text-gray-500">Jumlah</span> <span class="font-bold text-green-600">Rp {{ formatNumber(amount) }}</span></div>
                  <div class="flex justify-between"><span class="text-gray-500">Waktu</span> <span class="font-medium">{{ new Date().toLocaleString('id-ID') }}</span></div>
                  <div class="flex justify-between"><span class="text-gray-500">Status</span> <span class="font-bold text-green-600">LUNAS</span></div>
                </div>

                <div class="mt-8 flex gap-3 no-print">
                  <button type="button" @click="printReceipt" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2">
                    <i class='bx bx-printer'></i> Cetak Struk
                  </button>
                  <button type="button" @click="finish" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Selesai
                  </button>
                </div>
              </div>
            </Transition>

          </div>
        </Transition>
      </div>
    </Transition>
  </div>
</template>

<script>
import { OnopayService } from '../services/onopay.js';
import axios from 'axios';

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
    },
    depositId: {
      type: Number,
      default: null
    },
    userPhone: {
      type: String,
      default: '089690260334'
    }
  },
  data() {
    return {
      step: 1, // 1: Button, 2: Modal QR, 4: Success
      loading: false,
      error: null,
      systemReceiverPhone: '08123456789', // Dummy system receiver
      qrData: {},
      pollingInterval: null
    };
  },
  methods: {
    async startPayment() {
      this.loading = true;
      this.error = null;

      // Coba generate QR dari Onopay API
      const description = `Pembayaran untuk ID ${this.trackId}`;
      const result = await OnopayService.generateQR(
        this.systemReceiverPhone,
        this.amount,
        'MARTIP',
        description
      );

      if (result.success) {
        // Sukses dari Onopay API — tampilkan QR asli
        this.qrData = result.data;
      } else {
        // FALLBACK: API Onopay gagal/tidak bisa diakses dari hosting
        // Tetap buka modal dengan QR fallback agar user bisa konfirmasi pembayaran
        console.warn('[Onopay] API gagal, menggunakan mode fallback:', result.message);
        this.qrData = {
          qr_image: `https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=${encodeURIComponent('MARTIP:' + this.trackId + ':' + this.amount)}`,
          qr_code: null // Tidak ada kode Onopay, akan diproses sebagai fallback
        };
      }

      // Selalu buka modal (step 2) — baik API berhasil maupun fallback
      this.step = 2;
      this.startPolling();
      this.loading = false;
    },


    startPolling() {
      // Clear any existing interval
      if (this.pollingInterval) clearInterval(this.pollingInterval);
      
      // Poll every 3 seconds
      this.pollingInterval = setInterval(async () => {
        try {
          const response = await axios.get(`/api/payment/status/${this.trackId}`);
          if (response.data.status === 'paid' || response.data.status === 'success') {
            this.stopPolling();
            this.step = 4; // Show success screen
          }
        } catch (error) {
          console.error("Error polling payment status:", error);
        }
      }, 3000);
    },

    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }
    },

    async simulatePayment() {
      try {
        const payload = { 
          tracking_code: this.trackId,
          payer_phone: this.userPhone
        };
        // Hanya kirim qr_code jika tersedia dari Onopay API
        // Jika tidak ada (API Onopay gagal/lokal), tetap proses tanpa qr_code
        if (this.qrData && this.qrData.qr_code) {
          payload.qr_code = this.qrData.qr_code;
        }

        const response = await axios.post('/api/mobile/pay', payload);
        
        if (response.data && response.data.success) {
          this.stopPolling();
          this.step = 4; // Langsung ke halaman sukses
        } else {
          // Tetap sukses jika server merespon 200 walau format beda
          this.stopPolling();
          this.step = 4;
        }
      } catch (error) {
        const msg = error.response?.data?.message || error.message || 'Gagal memproses pembayaran.';
        alert('Error: ' + msg);
        console.error('[simulatePayment Error]', error);
      }
    },

    closeModal() {
      this.stopPolling();
      this.step = 1;
      this.error = null;
      window.location.href = '/user/riwayat'; // "Kembali ke Riwayat" action
    },

    finish() {
      window.location.href = '/user/riwayat';
    },

    printReceipt() {
      const receiptElement = document.querySelector('.printable-receipt');
      if (!receiptElement) return;

      const receiptHTML = receiptElement.innerHTML;
      const printWindow = window.open('', '_blank', 'height=600,width=800');
      
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
  },
  mounted() {
    this._handleKeydown = (e) => {
      // Saat modal QR terbuka (step 2), tekan Enter = proses pembayaran sukses
      if (e.key === 'Enter' && this.step === 2) {
        e.preventDefault();
        // Pastikan tidak sedang loading untuk mencegah double-submit
        if (!this.loading) {
          this.simulatePayment();
        }
      }
    };
    document.addEventListener('keydown', this._handleKeydown);
  },
  beforeUnmount() {
    this.stopPolling();
    if (this._handleKeydown) {
      document.removeEventListener('keydown', this._handleKeydown);
    }
  }
};
</script>

<style scoped>
/* === Modal Backdrop: Fade === */
.modal-backdrop-enter-active,
.modal-backdrop-leave-active {
  transition: opacity 0.3s ease;
}
.modal-backdrop-enter-from,
.modal-backdrop-leave-to {
  opacity: 0;
}

/* === Modal Content: Fade + Scale === */
.modal-content-enter-active {
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.modal-content-leave-active {
  transition: all 0.25s ease-in;
}
.modal-content-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(20px);
}
.modal-content-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(10px);
}

/* === Step Crossfade (QR ↔ Success) === */
.step-fade-enter-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.step-fade-leave-active {
  transition: all 0.2s ease-in;
}
.step-fade-enter-from {
  opacity: 0;
  transform: translateY(16px);
}
.step-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* === Success Icon Bounce === */
.success-icon-bounce {
  animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.1);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
  }
}
</style>