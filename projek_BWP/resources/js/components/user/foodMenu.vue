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

            <p class="text-muted small mb-3">
              <i class="bi bi-geo-alt me-1"></i>
              {{ item.location }}
            </p>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <div>
                <small class="text-muted fw-bold text-uppercase">Price from</small>
                <div class="fs-5 fw-bold text-primary">
                  Rp {{ item.price }}
                </div>
              </div>

              <router-link
                :to="{ path: '/schedule', query: { productId: item.id } }"
              >
                <button class="btn btn-primary rounded-pill fw-bold px-3">
                  Order
                </button>
              </router-link>
            </div>

          </div>
        </div>
      </div>
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
    category_id: 1
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
</script>
