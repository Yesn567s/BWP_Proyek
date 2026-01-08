<template>
  <div class="checkout-container">
    <div class="container-lg py-5">
      <h1 class="fw-bold mb-5">Checkout</h1>

      <div class="row g-4">
        <!-- Left: Order Summary -->
        <div class="col-lg-8">
          <!-- Cart Items -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
              <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
              <div v-if="cartItems.length === 0" class="text-center text-muted py-5">
                <p>Your cart is empty</p>
              </div>
              <div v-else>
                <div v-for="(item, index) in cartItems" :key="index" class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                  <div>
                    <h6 class="fw-bold mb-1" v-if="item.type === 'seat'">Seat Booking</h6>
                    <h6 class="fw-bold mb-1" v-else>Item</h6>
                    <small class="text-muted" v-if="item.type === 'seat'">
                      Seat: {{ item.row }}{{ item.number }} (ID: {{ item.id }})
                    </small>
                    <small class="text-muted" v-else>
                      {{ item.title || item.name }} (Qty: {{ item.quantity || 1 }}) (ID: {{ item.id }})
                    </small>
                  </div>
                  <span class="fw-bold">Rp {{ formatPrice((item.price ?? pricePerSeat) * (item.quantity || 1)) }}</span>
                </div>

                <!-- Subtotal, Tax, Total -->
                <div class="mt-4">
                  <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>Rp {{ formatPrice(subtotal) }}</span>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span>Tax (10%):</span>
                    <span>Rp {{ formatPrice(tax) }}</span>
                  </div>
                  <div v-if="discountAmount > 0" class="d-flex justify-content-between mb-3 text-success">
                    <span>Discount:</span>
                    <span>-Rp {{ formatPrice(discountAmount) }}</span>
                  </div>
                  <div class="d-flex justify-content-between pt-3 border-top">
                    <h5 class="fw-bold">Total:</h5>
                    <h5 class="fw-bold">Rp {{ formatPrice(totalPrice) }}</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Voucher Code -->
          <div class="card shadow-sm">
            <div class="card-header bg-light">
              <h5 class="mb-0">Promo Code</h5>
            </div>
            <div class="card-body">
              <div class="input-group">
                <input v-model="voucherCode" type="text" class="form-control" placeholder="Enter voucher code" @keyup.enter="applyVoucher">
                <button @click="applyVoucher" class="btn btn-primary" type="button">Apply</button>
              </div>
              <div v-if="voucherMessage" :class="['mt-2', voucherMessage.includes('applied') ? 'text-success' : 'text-danger']">
                {{ voucherMessage }}
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Payment QR Code -->
        <div class="col-lg-4">
          <div class="card shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-light">
              <h5 class="mb-0">Payment</h5>
            </div>
            <div class="card-body text-center">
              <div v-if="qrCodeUrl" class="mb-4">
                <p class="text-muted mb-3">Scan QR Code to complete payment</p>
                <img :src="qrCodeUrl" alt="Payment QR Code" class="img-fluid rounded border" style="max-width: 100%; height: auto;">
              </div>

              <div v-if="!qrCodeUrl && !loading" class="alert alert-info">
                <p class="mb-0">Generate payment QR code to proceed with checkout</p>
              </div>

              <div v-if="loading" class="text-center py-5">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden">Processing...</span>
                </div>
                <p class="mt-3 text-muted">Generating payment code...</p>
              </div>

              <!-- Amount to Pay -->
              <div class="bg-light p-3 rounded mb-4">
                <p class="text-muted mb-2">Total Amount</p>
                <h4 class="fw-bold text-primary">Rp {{ formatPrice(totalPrice) }}</h4>
              </div>

              <!-- Action Buttons -->
              <button 
                @click="generatePayment" 
                :disabled="cartItems.length === 0 || loading"
                class="btn btn-primary w-100 mb-2"
              >
                <span v-if="!loading">Generate Payment QR</span>
                <span v-else>Processing...</span>
              </button>

              <button 
                @click="completeCheckout" 
                :disabled="!qrCodeUrl || loading"
                class="btn btn-success w-100"
              >
                <span v-if="!loading">Complete Purchase</span>
                <span v-else>Completing...</span>
              </button>

              <router-link to="/" class="btn btn-outline-secondary w-100 mt-2">
                Continue Shopping
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title">✓ Order Successful</h5>
            <button type="button" class="btn-close" @click="showSuccessModal = false"></button>
          </div>
          <div class="modal-body text-center py-5">
            <div class="mb-4">
              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h4 class="fw-bold mb-3">Payment Successful!</h4>
            <p class="text-muted mb-4">Your order has been confirmed and saved to your account.</p>
            <p class="mb-1"><strong>Order ID:</strong> {{ successOrderId }}</p>
            <p class="text-muted">Check your email for order details</p>
          </div>
          <div class="modal-footer border-0">
            <router-link to="/userTickets" class="btn btn-primary">
              View My Tickets
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const cart = ref(null);
const cartItems = ref([]);
const scheduleId = ref(null);
const qrCodeUrl = ref(null);
const loading = ref(false);
const voucherCode = ref('');
const voucherMessage = ref('');
const appliedVoucher = ref(null);
const showSuccessModal = ref(false);
const successOrderId = ref(null);
const checkoutSource = ref(null); // 'seat' or 'food'

// Ensure axios sends session cookie + CSRF for web middleware
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}
axios.defaults.withCredentials = true;

const seatCount = computed(() => cartItems.value.filter(item => item.type === 'seat').length);

// Computed properties
const pricePerSeat = computed(() => {
  const seatWithPrice = cartItems.value.find(item => item.type === 'seat' && item.price);
  if (seatWithPrice) return seatWithPrice.price;
  if (!cart.value || seatCount.value === 0) return 0;
  return cart.value.totalPrice / seatCount.value;
});

const subtotal = computed(() => {
  return cartItems.value.reduce((sum, item) => {
    const qty = item.quantity ?? 1;
    const unitPrice = item.price ?? pricePerSeat.value;
    return sum + unitPrice * qty;
  }, 0);
});

const tax = computed(() => {
  return subtotal.value * 0.1; // 10% tax
});

const discountAmount = computed(() => {
  if (!appliedVoucher.value) return 0;
  
  if (appliedVoucher.value.discount_type === 'percent') {
    return (subtotal.value * appliedVoucher.value.discount_value) / 100;
  } else {
    return appliedVoucher.value.discount_value;
  }
});

const totalPrice = computed(() => {
  return subtotal.value + tax.value - discountAmount.value;
});

// Methods
const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const applyVoucher = async () => {
  if (!voucherCode.value.trim()) {
    voucherMessage.value = 'Please enter a voucher code';
    return;
  }

  try {
    loading.value = true;
    const res = await axios.get(`/api/vouchers/${voucherCode.value}`);
    appliedVoucher.value = res.data;
    console.log('Applied voucher:', res.data);
    voucherMessage.value = 'Voucher applied successfully!';
  } catch (error) {
    voucherMessage.value = 'Invalid voucher code';
    appliedVoucher.value = null;
  } finally {
    loading.value = false;
  }
};

const buildOrderItems = () => {
  return cartItems.value.flatMap(item => {
    const qty = item.quantity ?? 1;
    const unitPrice = item.price ?? pricePerSeat.value;
    const isSeat = item.type === 'seat';

    return Array.from({ length: qty }).map(() => ({
      schedule_id: isSeat ? scheduleId.value : null,
      product_id: !isSeat ? item.id : null,
      seat_id: isSeat ? item.id : null,
      price: unitPrice,
      quantity: 1
    }));
  });
};

const generatePayment = async () => {
  if (cartItems.value.length === 0) {
    alert('Your cart is empty');
    return;
  }

  try {
    loading.value = true;
    const orderItems = buildOrderItems();

    const orderData = {
      items: orderItems,
      total_price: totalPrice.value,
      voucher_id: appliedVoucher.value?.voucher_id || appliedVoucher.value?.id || null,
      discount_amount: discountAmount.value
    };

    qrCodeUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=Current_Order_${Date.now()}`;
  } catch (error) {
    console.error('Error generating payment:', error);
    alert('Failed to generate payment QR code');
  } finally {
    loading.value = false;
  }
};

const completeCheckout = async () => {
  try {
    loading.value = true;

    const orderItems = buildOrderItems();

    if (!orderItems.length) {
      alert('Your cart is empty');
      return;
    }

    // Submit order to database
    const response = await axios.post('/api/orders', {
      items: orderItems,
      total_price: totalPrice.value,
      voucher_id: appliedVoucher.value?.voucher_id || appliedVoucher.value?.id || null,
      discount_amount: discountAmount.value
    });

    successOrderId.value = response.data.order_id;
    console.log(response.data);
    showSuccessModal.value = response.data.success;

    // Clear cart from localStorage
    localStorage.removeItem('cart');
    cartItems.value = [];
    qrCodeUrl.value = null;

    if(showSuccessModal.value){
      setTimeout(() => {
      router.push('/userTickets'); // Ke userTickets atau ke home
    },5000);
    }
    
  } catch (error) {
    console.error('Error completing checkout:', error);
    const status = error?.response?.status;
    if (status === 401) {
      alert('Please log in before completing your purchase.');
      router.push('/login');
    } else {
      const message = error?.response?.data?.error || error?.response?.data?.message || 'Failed to complete order. Please try again.';
      alert(message);
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  // Decide single checkout source to avoid mixing seat tickets and food
  const savedCart = localStorage.getItem('cart');
  const hasQueryItems = !!route.query.items;

  if (hasQueryItems) {
    // Prioritize food checkout if query items exist
    try {
      const parsed = JSON.parse(route.query.items);
      if (Array.isArray(parsed)) {
        cartItems.value = parsed.map(item => ({
          id: item.id,
          name: item.title || item.name,
          title: item.title,
          price: Number(item.price) || 0,
          quantity: item.quantity ?? 1,
          type: 'food'
        }));
        checkoutSource.value = 'food';
      }
    } catch (err) {
      console.error('Error parsing query items:', err);
    }

    // Food checkout should not keep previous seat cart
    localStorage.removeItem('cart');
  } else if (savedCart) {
    try {
      cart.value = JSON.parse(savedCart);
      scheduleId.value = cart.value.scheduleId;

      if (cart.value.seats && Array.isArray(cart.value.seats) && cart.value.seats.length > 0) {
        const seatPrice = cart.value.totalPrice && cart.value.seats.length
          ? cart.value.totalPrice / cart.value.seats.length
          : 0;

        cartItems.value = cart.value.seats.map(seat => ({
          ...seat,
          type: 'seat',
          price: seat.price ?? seatPrice,
          quantity: 1
        }));
        checkoutSource.value = 'seat';
      } else if (cart.value.id && cart.value.name) {
        cartItems.value = [{
          id: cart.value.id,
          name: cart.value.name,
          type: 'item',
          price: cart.value.totalPrice,
          quantity: 1
        }];
        checkoutSource.value = 'seat';
      }
    } catch (err) {
      console.error('Error parsing cart:', err);
      alert('Invalid cart data');
      router.push('/');
    }
  }

  // If no items in cart, redirect
  if (cartItems.value.length === 0) {
    setTimeout(() => {
      alert('Your cart is empty. Please select items first.');
      router.push('/');
    }, 500);
  }
});

// Leaving checkout should clear cart so it does not accumulate
onBeforeUnmount(() => {
  localStorage.removeItem('cart');
  cartItems.value = [];
});
</script>

<style scoped>
.checkout-container {
  background-color: #f8f9fa;
  min-height: 100vh;
}

.sticky-top {
  top: 20px !important;
}

.card {
  border: none;
  border-radius: 12px;
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
}

.card-header {
  border-bottom: 1px solid #e0e0e0;
  border-radius: 12px 12px 0 0 !important;
}

.card-body {
  padding: 1.5rem;
}

.btn {
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #007bff;
  border: none;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
}

.btn-success {
  background-color: #28a745;
  border: none;
}

.btn-success:hover:not(:disabled) {
  background-color: #218838;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.input-group .form-control {
  border-radius: 8px 0 0 8px;
  border: 1px solid #ddd;
}

.input-group .btn {
  border-radius: 0 8px 8px 0;
}

.spinner-border {
  color: #007bff;
}

.modal {
  display: none;
}

.modal.show {
  display: block;
}

@media (max-width: 768px) {
  .sticky-top {
    position: relative !important;
    top: 0 !important;
  }
}
</style>
