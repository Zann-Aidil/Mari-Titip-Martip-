<!-- resources/js/components/OnopayPaymentForm.vue -->

<template>
  <div class="onopay-wrapper">

    <!-- ======= STEP 1: Tombol Bayar Sekarang ======= -->
    <button
      type="button"
      @click="startPayment"
      :disabled="loading"
      class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-200"
    >
      <span v-if="loading" class="animate-spin text-xl">⏳</span>
      <i v-else class='bx bx-lock-alt text-xl'></i>
      {{ loading ? 'Memproses...' : 'Bayar Sekarang' }}
    </button>

    <div v-if="error && step === 1" class="text-red-500 text-sm mt-2 text-center">
      {{ error }}
    </div>

    <!-- ======= MODAL (Step 2 & 4) ======= -->
    <Transition name="modal-backdrop">
      <div
        v-if="step > 1"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        @click.self="closeModal"
      >
        <Transition name="modal-content" appear>
          <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto relative">

            <Transition name="step-fade" mode="out-in">

              <!-- ======= STEP 2: QR Code ======= -->
              <div v-if="step === 2" key="step-qr" class="text-center">

                <!-- Header (double-click untuk simulasi) -->
                <div
                  @dblclick="simulatePayment"
                  class="bg-blue-600 text-white py-4 px-6 rounded-t-2xl font-bold text-lg shadow-sm cursor-pointer select-none"
                >
                  Metode Pembayaran
                </div>

                <div class="p-8">
                  <!-- Logo OnoPay -->
                  <div class="flex justify-center items-center gap-3 mb-2">
                    <div class="text-blue-600 font-black text-3xl tracking-tighter flex flex-col leading-none items-center">
                      <div class="flex gap-1">
                        <span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span>
                        <span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span>
                      </div>
                      <div class="flex gap-1 mt-1">
                        <span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span>
                        <span class="w-3.5 h-3.5 bg-blue-600 rounded-sm"></span>
                      </div>
                    </div>
                    <div class="text-blue-800 font-black text-3xl tracking-tight">
                      OnoPay<span class="text-blue-500 font-bold italic ml-1">QR</span>
                    </div>
                  </div>

                  <p class="text-sm text-gray-500 mb-6">Scan QR Code untuk membayar</p>

                  <!-- Total Tagihan -->
                  <div class="bg-blue-50 border border-blue-100 py-3 px-4 rounded-xl mb-6 flex justify-center items-center shadow-inner">
                    <span class="font-bold text-blue-900 text-lg">
                      Total Tagihan: <span class="font-bold text-blue-600">Rp {{ formatNumber(amount) }}</span>
                    </span>
                  </div>

                  <!-- QR Code Image -->
                  <div class="qr-image flex justify-center mb-8 relative">
                    <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-md opacity-50 transform scale-105"></div>
                    <img
                      :src="qrData.qr_image"
                      alt="QR Code"
                      class="w-64 h-64 border-4 border-white shadow-lg rounded-2xl relative z-10"
                    />
                  </div>

                  <!-- Tombol-tombol -->
                  <div class="flex flex-col gap-3 mt-4">
                    <a
                      :href="'/user/pembayaran?deposit_id=' + depositId"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm shadow-blue-200"
                    >
                      <i class='bx bx-upload text-lg'></i> Upload Bukti Pembayaran
                    </a>

                    <button
                      type="button"
                      @click="closeModal"
                      class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm"
                    >
                      <i class='bx bx-arrow-back text-lg'></i> Kembali ke Riwayat
                    </button>

                    <!-- Tombol simulasi tersembunyi -->
                    <button
                      type="button"
                      @click="simulatePayment"
                      title="Klik untuk simulasi sukses"
                      class="w-full text-center mt-2 text-xs font-semibold tracking-widest text-gray-300 hover:text-gray-400 cursor-pointer"
                    >
                      MARTIP v1.0.0
                    </button>
                  </div>
                </div>
              </div>

              <!-- ======= STEP 4: Sukses ======= -->
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
                  <div class="flex justify-between">
                    <span class="text-gray-500">ID Titipan</span>
                    <span class="font-medium">{{ trackId }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-bold text-green-600">Rp {{ formatNumber(amount) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Waktu</span>
                    <span class="font-medium">{{ new Date().toLocaleString('id-ID') }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-bold text-green-600">LUNAS</span>
                  </div>
                </div>

                <div class="mt-8 flex gap-3 no-print">
                  <button
                    type="button"
                    @click="printReceipt"
                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2"
                  >
                    <i class='bx bx-printer'></i> Cetak Struk
                  </button>
                  <button
                    type="button"
                    @click="finish"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition"
                  >
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
      default: ''
    },
    merchantPhone: {
      type: String,
      default: '082213993917'
    }
  },
  data() {
    return {
      step: 1,          // 1: Tombol, 2: Modal QR, 4: Sukses
      loading: false,
      error: null,
      qrData: {},
      pollingInterval: null
    };
  },
  methods: {

    // STEP 1 → 2: Generate QR Code via backend (bukan langsung ke Onopay dari browser)
    async startPayment() {
      this.loading = true;
      this.error   = null;

      try {
        const res = await axios.post('/api/payment/generate-qr', {
          deposit_id: this.depositId
        });

        // Baik sukses (Onopay asli) maupun fallback (QR publik), tetap tampilkan QR
        this.qrData = {
          qr_image: res.data.qr_image,
          qr_code:  res.data.qr_code ?? null
        };

        if (!res.data.success) {
          console.warn('[Onopay] API tidak tersedia, menggunakan QR fallback:', res.data.message);
        }
      } catch (e) {
        // Fallback lokal jika endpoint juga gagal
        const fallbackData = encodeURIComponent('MARTIP:' + this.trackId + ':' + this.amount);
        this.qrData = {
          qr_image: `https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=${fallbackData}`,
          qr_code:  null
        };
        console.warn('[Onopay] Endpoint generate-qr error, fallback lokal aktif');
      }

      this.step = 2;
      this.startPolling();
      this.loading = false;
    },

    // Polling: cek status pembayaran tiap 3 detik
    startPolling() {
      if (this.pollingInterval) clearInterval(this.pollingInterval);

      this.pollingInterval = setInterval(async () => {
        try {
          const res = await axios.get(`/api/payment/status/${this.trackId}`);
          if (res.data.status === 'paid' || res.data.status === 'success') {
            this.stopPolling();
            this.step = 4;
          }
        } catch (e) {
          console.error('Polling error:', e);
        }
      }, 3000);
    },

    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }
    },

    // Simulasi pembayaran sukses (Enter / klik "MARTIP v1.0.0" / double-click header)
    // Menggunakan nomor HP user yang terdaftar sebagai payer_phone
    async simulatePayment() {
      try {
        const payload = {
          tracking_code: this.trackId,
          payer_phone:   this.userPhone   // nomor HP dari registrasi user
        };

        // Kirim qr_code jika tersedia dari Onopay asli
        if (this.qrData && this.qrData.qr_code) {
          payload.qr_code = this.qrData.qr_code;
        }

        await axios.post('/api/mobile/pay', payload);
        this.stopPolling();
        this.step = 4;
      } catch (e) {
        const msg = e.response?.data?.message || e.message || 'Gagal memproses pembayaran.';
        alert('Error: ' + msg);
        console.error('[simulatePayment Error]', e);
      }
    },

    closeModal() {
      this.stopPolling();
      this.step  = 1;
      this.error = null;
      window.location.href = '/user/riwayat';
    },

    finish() {
      window.location.href = '/user/riwayat';
    },

    printReceipt() {
      const el = document.querySelector('.printable-receipt');
      if (!el) return;

      let stylesHtml = '';
      document.querySelectorAll('link[rel="stylesheet"], style').forEach(node => {
        stylesHtml += node.outerHTML;
      });

      const win = window.open('', '_blank', 'height=600,width=800');
      win.document.write(`
        <html>
          <head>
            <title>Struk Pembayaran MARTIP</title>
            ${stylesHtml}
            <style>body { background: white !important; padding: 40px; color: black; } .no-print { display: none !important; }</style>
          </head>
          <body>
            <div class="max-w-md mx-auto border border-gray-200 rounded-2xl p-8 shadow-sm">
              ${el.innerHTML}
            </div>
            <script>window.onload=function(){ setTimeout(function(){ window.print(); window.close(); }, 500); }<\/script>
          </body>
        </html>
      `);
      win.document.close();
    },

    formatNumber(num) {
      if (!num) return '0';
      return new Intl.NumberFormat('id-ID').format(num);
    }
  },

  mounted() {
    this._handleKeydown = (e) => {
      // Enter di step 2 (QR tampil) → langsung proses pembayaran sukses
      if (e.key === 'Enter' && this.step === 2 && !this.loading) {
        e.preventDefault();
        this.simulatePayment();
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
/* Modal Backdrop */
.modal-backdrop-enter-active, .modal-backdrop-leave-active { transition: opacity 0.3s ease; }
.modal-backdrop-enter-from, .modal-backdrop-leave-to { opacity: 0; }

/* Modal Content */
.modal-content-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.modal-content-leave-active { transition: all 0.25s ease-in; }
.modal-content-enter-from { opacity: 0; transform: scale(0.9) translateY(20px); }
.modal-content-leave-to { opacity: 0; transform: scale(0.95) translateY(10px); }

/* Step Crossfade */
.step-fade-enter-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.step-fade-leave-active { transition: all 0.2s ease-in; }
.step-fade-enter-from { opacity: 0; transform: translateY(16px); }
.step-fade-leave-to { opacity: 0; transform: translateY(-10px); }

/* Success Bounce */
.success-icon-bounce { animation: bounceIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); }
@keyframes bounceIn {
  0%   { opacity: 0; transform: scale(0.3); }
  50%  { opacity: 1; transform: scale(1.1); }
  70%  { transform: scale(0.9); }
  100% { transform: scale(1); }
}
</style>