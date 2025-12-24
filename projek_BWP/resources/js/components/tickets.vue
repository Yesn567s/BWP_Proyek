<style scoped>
.cat-icon {
  width: 20px;
  height: 20px;
  object-fit: contain;
}
</style>

<script setup>
import axios from 'axios'
import { ref, computed } from 'vue'
import { onMounted } from 'vue'

const selectedCat = ref('All')
const search = ref('')

const categories = ref([])

onMounted(async () => {
  const res = await axios.get('/api/categories')
  categories.value = res.data
})

const tickets = ref([])

onMounted(async () => {
  const res = await axios.get('/api/tickets')
  tickets.value = res.data
})

const filteredItems = computed(() => {
  return tickets.value.filter(item => {
    const matchesCat =
      selectedCat.value === 'All' || item.category === selectedCat.value

    const matchesSearch =
      item.title.toLowerCase().includes(search.value.toLowerCase())

    return matchesCat && matchesSearch
  })
})


</script>

<template>
    <h6>halo ini tickets</h6>

    <div class="container py-5">

    <!-- Header -->
    <div class="row align-items-center mb-4 g-3">
      <div class="col-md">
        <h1 class="fw-bold">Explore Tickets</h1>
        <p class="text-muted mb-0">Discover amazing events and destinations</p>
      </div>

      <!-- Search -->
      <div class="col-md-5 position-relative">
        <input
          type="text"
          class="form-control form-control-lg ps-5 rounded-pill"
          placeholder="Search for events, parks, or movies..."
          v-model="search"
        />
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      </div>
    </div>

    <!-- Categories -->
    <div class="d-flex gap-2 overflow-auto mb-4 pb-2">
      <button
        class="btn rounded-pill fw-bold"
        :class="selectedCat === 'All' ? 'btn-primary' : 'btn-outline-secondary'"
        @click="selectedCat = 'All'"
      >
        All Categories
      </button>

      <button
        v-for="cat in categories"
        :key="cat.id"
        class="btn rounded-pill fw-bold text-nowrap"
        :class="selectedCat ===  cat.category_name  ? 'btn-primary' : 'btn-outline-secondary'"
        @click="selectedCat = cat.category_name"
      >
        <img :src="`/${cat.icons}`" class="cat-icon"/> {{ cat.category_name }}
      </button>
    </div>

    <!-- Tickets Grid -->
    <div class="row g-4">
      <div
        v-for="item in filteredItems"
        :key="item.id"
        class="col-sm-6 col-lg-4 col-xl-3"
      >
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

          <!-- Image -->
          <div class="position-relative">
            <img
              :src="item.imageUrl"
              class="card-img-top"
              style="height: 200px; object-fit: cover"
            />
            <div class="position-absolute top-0 end-0 bg-white px-2 py-1 m-3 rounded shadow-sm small fw-bold">
                ⭐ {{ item.rating ?? '—' }}
            </div>
          </div>

          <!-- Body -->
          <div class="card-body">
            <span class="text-primary text-uppercase small fw-bold">
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
                <div class="fs-5 fw-bold text-primary">Rp {{ item.price }}</div>
              </div>
              <router-link :to="{ path: '/schedule', query: { productId: item.id } }">
                <button class="btn btn-primary rounded-pill fw-bold px-3">Book</button>
              </router-link>
              
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</template>