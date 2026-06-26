<!-- resources/js/components/OnopayPaymentForm.vue -->

<template>
  <div class="onopay-wrapper">

    <!-- ======= STEP 1: Tombol Bayar ======= -->
    <button
      v-if="step === 1"
      type="button"
      @click="startPayment"
      :disabled="loading"
      class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-200"
    >
      <span v-if="loading" class="animate-spin">⏳</span>
      <i v-else class='bx bx-wallet-alt text-xl'></i>
      {{ loading ? 'Mengecek saldo Onopay...' : 'Bayar dengan Saldo Onopay' }}
    </button>

    <div v-if="error && step === 1" class="mt-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3 flex items-start gap-2">
      <i class='bx bx-error-circle text-lg flex-shrink-0 mt-0.5'></i>
      <span>{{ error }}</span>
    </div>

    <!-- ======= MODAL (Step 2, 3, 4) ======= -->
    <Transition name="modal-backdrop">
      <div
        v-if="step > 1"
        class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4"
        @click.self="closeModal"
      >
        <Transition name="modal-content" appear>
          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            <!-- ======= STEP 2: Konfirmasi Saldo ======= -->
            <Transition name="step-fade" mode="out-in">
              <div v-if="step === 2" key="step-confirm">

                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-5 px-6">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                      <i class='bx bx-wallet-alt text-2xl'></i>
                    </div>
                    <div>
                      <h3 class="font-bold text-lg">Konfirmasi Pembayaran</h3>
                      <p class="text-blue-100 text-xs">Bayar dari saldo Onopay kamu</p>
                    </div>
                  </div>
                </div>

                <div class="p-6 space-y-4">

                  <!-- Nomor Onopay user -->
                  <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                    <div>
                      <p class="text-xs text-blue-500 font-medium mb-0.5">Nomor Onopay (dari registrasi)</p>
                      <p class="font-bold text-blue-900 text-base tracking-wide">{{ balanceData.payer_phone }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                      <i class='bx bx-phone text-blue-600 text-xl'></i>
                    </div>
                  </div>

                  <!-- Saldo vs Tagihan -->
                  <div class="grid grid-cols-2 gap-3">
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                      <p class="text-xs text-green-600 font-medium mb-1">Saldo Onopay</p>
                      <p class="font-bold text-green-700 text-lg">Rp {{ formatNumber(balanceData.balance) }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-4 text-center">
                      <p class="text-xs text-orange-600 font-medium mb-1">Tagihan</p>
                      <p class="font-bold text-orange-700 text-lg">Rp {{ formatNumber(amount) }}</p>
                    </div>
                  </div>

                  <!-- Status saldo -->
                  <div v-if="balanceData.balance >= amount" class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl p-3">
                    <i class='bx bx-check-circle text-green-600 text-xl'></i>
                    <div>
                      <p class="text-green-700 font-semibold text-sm">Saldo mencukupi</p>
                      <p class="text-green-600 text-xs">Sisa setelah bayar: Rp {{ formatNumber(balanceData.balance - amount) }}</p>
                    </div>
                  </div>
                  <div v-else class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl p-3">
                    <i class='bx bx-error-circle text-red-500 text-xl'></i>
                    <div>
                      <p class="text-red-700 font-semibold text-sm">Saldo tidak mencukupi</p>
                      <p class="text-red-600 text-xs">Perlu top-up minimal Rp {{ formatNumber(amount - balanceData.balance) }} lagi</p>
                    </div>
                  </div>

                  <!-- Error dari proses bayar -->
                  <div v-if="error" class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-xl p-3">
                    <i class='bx bx-error text-red-500 text-lg flex-shrink-0 mt-0.5'></i>
                    <p class="text-red-700 text-sm">{{ error }}</p>
                  </div>

                  <!-- Tombol Konfirmasi -->
                  <button
                    v-if="balanceData.balance >= amount"
                    type="button"
                    @click="confirmPayment"
                    :disabled="loading"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2"
                  >
                    <span v-if="loading" class="animate-spin">⏳</span>
                    <i v-else class='bx bx-check-shield text-xl'></i>
                    {{ loading ? 'Memproses pembayaran...' : 'Konfirmasi & Bayar Sekarang' }}
                  </button>

                  <!-- Tombol Batal -->
                  <button
                    type="button"
                    @click="closeModal"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition text-sm"
                  >
                    Batal
                  </button>

                  <!-- Tombol simulasi darurat (hidden, double-click label untuk aktifkan) -->
                  <button
                    type="button"
                    @dblclick="simulateFallback"
                    class="w-full text-center text-xs text-gray-200 hover:text-gray-300 cursor-pointer mt-1"
                    title="Double-click untuk simulasi"
                  >
                    MARTIP v1.0.0
                  </button>
                </div>
              </div>

              <!-- ======= STEP 3: Processing ======= -->
              <div v-else-if="step === 3" key="step-processing" class="p-10 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-5">
                  <svg class="animate-spin w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Memproses Pembayaran</h3>
                <p class="text-gray-500 text-sm mb-1">Saldo Onopay <strong>{{ balanceData.payer_phone }}</strong></p>
                <p class="text-gray-400 text-xs">Harap tunggu, jangan tutup halaman ini...</p>
              </div>

              <!-- ======= STEP 4: Sukses ======= -->
              <div v-else-if="step === 4" key="step-success" class="step-success printable-receipt p-6">
                <div class="text-center mb-6">
                  <div class="success-icon-bounce w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-check text-5xl'></i>
                  </div>
                  <h2 class="text-2xl font-bold text-gray-900">Pembayaran Berhasil!</h2>
                  <p class="text-gray-500 text-sm mt-1">Saldo Onopay kamu telah terpotong</p>
                </div>

                <div class="transaction-details bg-gray-50 p-5 rounded-xl border border-gray-100 text-sm space-y-3 mb-6">
                  <h3 class="font-bold text-center text-gray-800 border-b pb-3 mb-3">STRUK TRANSAKSI</h3>
                  <div class="flex justify-between">
                    <span class="text-gray-500">ID Titipan</span>
                    <span class="font-semibold">{{ receiptData.receipt_no || trackId }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Nomor Payer</span>
                    <span class="font-semibold text-blue-600">{{ receiptData.payer_phone || balanceData.payer_phone }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah Bayar</span>
                    <span class="font-bold text-green-600">Rp {{ formatNumber(receiptData.amount || amount) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Merchant</span>
                    <span class="font-semibold text-gray-700">{{ receiptData.merchant || 'MARTIP' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Waktu</span>
                    <span class="font-medium">{{ new Date().toLocaleString('id-ID') }}</span>
                  </div>
                  <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-500">Status</span>
                    <span class="font-bold text-green-600 flex items-center gap-1">
                      <i class='bx bx-check-circle'></i> LUNAS
                    </span>
                  </div>
                </div>

                <div class="flex gap-3 no-print">
                  <button type="button" @click="printReceipt" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                    <i class='bx bx-printer'></i> Cetak Struk
                  </button>
                  <button type="button" @click="finish" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm">
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
      step: 1,       // 1: Tombol, 2: Konfirmasi saldo, 3: Processing, 4: Sukses
      loading: false,
      error: null,
      balanceData: { balance: 0, payer_phone: '' },
      receiptData: {}
    };
  },
  methods: {

    // STEP 1 → 2: Cek saldo Onopay user berdasarkan nomor HP registrasi
    async startPayment() {
      this.loading = true;
      this.error   = null;

      try {
        const res = await axios.get('/api/user/onopay-balance');

        if (res.data && res.data.success) {
          this.balanceData = {
            balance:     res.data.data?.balance ?? 0,
            payer_phone: res.data.payer_phone ?? this.userPhone
          };
          this.step = 2; // Buka modal konfirmasi
        } else {
          this.error = res.data?.message ?? 'Gagal mengecek saldo Onopay. Pastikan nomor HP kamu sudah diisi di profil.';
        }
      } catch (e) {
        this.error = e.response?.data?.message ?? 'Tidak dapat terhubung ke server. Coba lagi.';
      }

      this.loading = false;
    },

    // STEP 2 → 3 → 4: Konfirmasi → proses bayar dari saldo Onopay user
    async confirmPayment() {
      this.loading = true;
      this.error   = null;
      this.step    = 3; // Tampilkan loading

      try {
        const res = await axios.post('/api/payment/process', {
          deposit_id: this.depositId
        });

        if (res.data && res.data.success) {
          this.receiptData = res.data.data ?? {};
          this.step = 4; // Sukses
        } else {
          this.error = res.data?.message ?? 'Pembayaran gagal.';
          this.step  = 2; // Kembali ke konfirmasi
        }
      } catch (e) {
        this.error = e.response?.data?.message ?? 'Pembayaran gagal. Coba lagi.';
        this.step  = 2; // Kembali ke konfirmasi
      }

      this.loading = false;
    },

    // Fallback darurat (double-click "MARTIP v1.0.0") — untuk demo tanpa Onopay real
    async simulateFallback() {
      this.step    = 3;
      this.loading = true;
      try {
        const payload = { tracking_code: this.trackId, payer_phone: this.balanceData.payer_phone || this.userPhone };
        await axios.post('/api/mobile/pay', payload);
        this.receiptData = { receipt_no: this.trackId, amount: this.amount, payer_phone: this.balanceData.payer_phone, merchant: 'MARTIP' };
        this.step = 4;
      } catch (e) {
        this.error = 'Simulasi gagal: ' + (e.response?.data?.message || e.message);
        this.step  = 2;
      }
      this.loading = false;
    },

    closeModal() {
      if (this.step === 4) {
        window.location.href = '/user/riwayat';
        return;
      }
      this.step  = 1;
      this.error = null;
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
            <style>body { background: white !important; padding: 40px; } .no-print { display: none !important; }</style>
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
      if (!num && num !== 0) return '0';
      return new Intl.NumberFormat('id-ID').format(num);
    }
  },

  mounted() {
    this._handleKeydown = (e) => {
      // Enter di step 2 (konfirmasi) → langsung proses bayar
      if (e.key === 'Enter' && this.step === 2 && !this.loading) {
        e.preventDefault();
        if (this.balanceData.balance >= this.amount) {
          this.confirmPayment();
        }
      }
    };
    document.addEventListener('keydown', this._handleKeydown);
  },

  beforeUnmount() {
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
.step-fade-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
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