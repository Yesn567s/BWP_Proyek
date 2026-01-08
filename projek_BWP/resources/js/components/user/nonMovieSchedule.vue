<template>
  <div class="container py-5">
    <!-- BACK BUTTON -->
    <router-link to="/food-menu" class="btn btn-outline-secondary mb-4">
      ← Back to Menu
    </router-link>

    <div v-if="loading" class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="product" class="row g-4">
      <!-- LEFT: PRODUCT IMAGE -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <img
            :src="product.imageUrl"
            class="card-img"
            style="height: 400px; object-fit: cover"
          />
        </div>
      </div>

      <!-- RIGHT: PRODUCT DETAILS -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
          <span class="text-primary small fw-bold text-uppercase">
            {{ product.category }}
          </span>

          <h1 class="fw-bold mt-3">{{ product.title }}</h1>

          <div class="fs-4 fw-bold text-primary mt-3">
            Rp {{ product.price?.toLocaleString('id-ID') }}
          </div>

          <div class="mt-4 pt-4 border-top">
            <p class="text-muted">
              {{ product.description || 'Delicious food & beverage item available at our venue.' }}
            </p>
          </div>

          <!-- QUANTITY SELECTOR -->
          <div class="mt-4 pt-4 border-top">
            <label class="fw-bold mb-2">Quantity</label>
            <input
              type="number"
              min="1"
              class="form-control form-control-lg"
              v-model.number="quantity"
              @change="quantity = Math.max(1, quantity || 1)"
            />
          </div>

          <!-- TOTAL PRICE -->
          <div class="mt-4 pt-4 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Subtotal:</span>
              <span class="fs-5 fw-bold">
                Rp {{ (product.price * quantity)?.toLocaleString('id-ID') }}
              </span>
            </div>
          </div>

          <!-- ORDER BUTTON -->
          <div class="mt-4">
            <button 
              class="btn btn-primary btn-lg w-100 rounded-pill fw-bold"
              @click="placeOrder"
            >
              Checkout
            </button>
          </div>

          <!-- BACK TO MENU -->
          <router-link to="/food-menu" class="btn btn-link w-100 mt-2">
            Continue Shopping
          </router-link>
        </div>
      </div>
    </div>

    <div v-else class="text-center text-muted py-5">
      <p>Product not found.</p>
      <router-link to="/food-menu" class="btn btn-primary mt-3">Back to Menu</router-link>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const quantity = ref(1)
const loading = ref(true)

onMounted(async () => {
  try {
    const productId = route.query.productId
    if (productId) {
      const res = await axios.get(`/api/food/${productId}`)
      product.value = res.data
    }
  } catch (err) {
    console.error('Error fetching product:', err)
    product.value = null
  } finally {
    loading.value = false
  }
})

const placeOrder = async () => {
  try {
    // You can add to cart logic here
    alert(`Proceeding to checkout with ${quantity.value} ${product.value.title}(s).`)
    // Optionally navigate to cart or checkout
    // router.push({ name: 'cart' })
  } catch (err) {
    console.error('Error placing order:', err)
    alert('Failed to add to cart')
  }
}
</script>

<style scoped>
.container {
  background: #f8f9fa;
  min-height: 100vh;
}

.card {
  border-radius: 24px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
}

.btn-outline-secondary {
  border-radius: 999px;
  font-weight: 600;
}

.btn-primary {
  background: #007bff;
  border: none;
}

.btn-primary:hover {
  background: #0056b3;
}

.spinner-border {
  color: #007bff;
}
</style>
