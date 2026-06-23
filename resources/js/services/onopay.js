import axios from 'axios';

const API_BASE_URL = 'https://onopay.web.id/api/v1';

export const OnopayService = {
  /**
   * Generate a QR Code for payment
   * @param {string} phoneNumber - Receiver's phone number
   * @param {number} amount - Payment amount
   * @param {string} merchantCode - Optional merchant code
   * @param {string} description - Optional description
   * @returns {Promise<Object>} Result object with success status and data/message
   */
  async generateQR(phoneNumber, amount, merchantCode = '', description = '') {
    try {
      const response = await axios.post(`${API_BASE_URL}/payment/qr/generate`, {
        phone_number: phoneNumber,
        amount: amount,
        merchant_code: merchantCode,
        description: description,
        qr_mode: 'single_use'
      });

      return response.data; // Expected format: { success: true/false, message: '...', data: { ... } }
    } catch (error) {
      if (error.response && error.response.data) {
        return error.response.data;
      }
      return {
        success: false,
        message: error.message || 'Terjadi kesalahan saat menghubungi server Onopay'
      };
    }
  },

  /**
   * Process a payment using a QR Code
   * @param {string} qrCode - The generated QR Code ID
   * @param {string} payerPhone - Payer's phone number
   * @returns {Promise<Object>} Result object with success status and data/message
   */
  async pay(qrCode, payerPhone) {
    try {
      const response = await axios.post(`${API_BASE_URL}/payment/qr/pay`, {
        qr_code: qrCode,
        payer_phone: payerPhone
      });

      return response.data;
    } catch (error) {
      if (error.response && error.response.data) {
        return error.response.data;
      }
      return {
        success: false,
        message: error.message || 'Terjadi kesalahan saat memproses pembayaran'
      };
    }
  }
};
