<template>
  <div class="container py-5">

    <!-- HEADER -->
    <div class="row align-items-center mb-4 g-3">
      <div class="col-md">
        <h1 class="fw-bold">Explore Food & Beverages</h1>
        <p class="text-muted mb-0">
          Restaurants, snacks, drinks & culinary experiences
        </p>
      </div>

      <!-- SEARCH -->
      <div class="col-md-5 position-relative">
        <input
          type="text"
          class="form-control form-control-lg ps-5 rounded-pill"
          placeholder="Search for any movies here..."
          v-model="search"
        />
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      </div>
    </div>

    <!-- CATEGORIES -->
    <div class="d-flex gap-2 overflow-auto mb-4 pb-2 category-scroll">

      <!-- ALL FOOD -->
      <!-- <button
        class="btn rounded-pill fw-bold"
        :class="!route.query.category ? 'btn-primary' : 'btn-outline-secondary'"
        @click="router.push({ name: 'food' })"
      >
        All Food
      </button> -->

      <!-- FOOD CATEGORIES -->
      <!-- <button
        v-for="cat in foodCategories"
        :key="cat.category_id"
        class="btn rounded-pill fw-bold text-nowrap"
        :class="route.query.category == cat.category_id
          ? 'btn-primary'
          : 'btn-outline-secondary'"
        @click="router.push({ name: 'food', query: { category: cat.category_id } })"
      >
        <img :src="`/${cat.icons}`" class="cat-icon" />
        {{ cat.category_name }}
      </button> -->

    </div>

    <!-- TICKETS GRID -->
    <div class="row g-4">
      <div
        v-for="item in filteredItems"
        :key="item.id"
        class="col-sm-6 col-lg-4 col-xl-3"
      >
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden ticket-card">

          <!-- IMAGE -->
          <img
            :src="item.imageUrl"
            class="card-img-top"
            style="height:200px; object-fit:cover"
          />

          <!-- BODY -->
          <div class="card-body">
            <span class="text-primary small fw-bold text-uppercase">
              {{ item.category }}
            </span>

            <h5 class="fw-bold mt-1">{{ item.title }}</h5>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <div>
                <small class="text-muted fw-bold text-uppercase">Price from</small>
                <div class="fs-5 fw-bold text-primary">
                  Rp {{ item.price }}
                </div>
              </div>

              <div class="quantity-stepper">
                <button
                  type="button"
                  class="stepper-btn"
                  @click="changeQuantity(item, -1)"
                >
                  –
                </button>
                <span class="stepper-value">{{ item.quantity || 0 }}</span>
                <button
                  type="button"
                  class="stepper-btn"
                  @click="changeQuantity(item, 1)"
                >
                  +
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- CHECKOUT ACTION -->
    <div class="d-flex justify-content-end mt-4">
      <button
        class="btn btn-primary btn-lg rounded-pill px-4 fw-bold"
        :disabled="selectedItems.length === 0"
        @click="checkout"
      >
        Checkout
      </button>
    </div>

  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const search = ref('')
const foods = ref([])

const foodCategories = ref([
  { category_id: 1, category_name: 'Snacks', icons: 'icons/snack.png' },
  { category_id: 2, category_name: 'Drinks', icons: 'icons/drink.png' },
  { category_id: 3, category_name: 'Meals', icons: 'icons/meal.png' }
])

onMounted(async () => {
  const res = await axios.get('/api/food')
  foods.value = res.data.map(item => ({
    id: item.id,
    title: item.title,
    price: item.price,
    imageUrl: item.imageUrl,
    category: 'Food & Beverage',
    location: 'All venues',
    category_id: 1,
    hasSchedule: false, // Food & Bev items don't have schedules
    quantity: 0
  }))
})

const filteredItems = computed(() => {
  let result = foods.value

  if (search.value) {
    result = result.filter(item =>
      item.title.toLowerCase().includes(search.value.toLowerCase())
    )
  }

  if (route.query.category) {
    result = result.filter(
      item => item.category_id == route.query.category
    )
  }

  return result
})

const selectedItems = computed(() =>
  foods.value.filter(item => (item.quantity || 0) > 0)
)

// Calculate valid_until as 3 days from today
const getValidUntil = () => {
  const date = new Date()
  date.setDate(date.getDate() + 3)
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

const checkout = () => {
  if (!selectedItems.value.length) return

  const validUntil = getValidUntil()
  const itemsWithValidUntil = selectedItems.value.map(item => ({
    ...item,
    valid_until: validUntil,
    type: 'food'
  }))

  router.push({ name: 'checkout', query: { items: JSON.stringify(itemsWithValidUntil) } })
}

const changeQuantity = (item, delta) => {
  const next = Math.max(0, (item.quantity || 0) + delta)
  item.quantity = next
}
</script>

<style scoped>
.quantity-stepper {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.stepper-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid #dcdcdc;
  background: #f5f5f5;
  color: #6c6c6c;
  font-weight: 600;
  line-height: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
}

.stepper-btn:hover {
  background: #e9e9e9;
  color: #333;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.stepper-btn:active {
  background: #dedede;
}

.stepper-value {
  min-width: 24px;
  text-align: center;
  font-weight: 600;
  color: #333;
}
</style>
