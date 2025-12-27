<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

/* state */
const items = ref([])
const search = ref('')

/* fetch movies */
onMounted(async () => {
  try {
    const res = await axios.get('/api/movies')
    items.value = res.data
  } catch (err) {
    console.error('Failed to load movies', err)
  }
})

/* search filter */
const filteredItems = computed(() => {
  if (!search.value) return items.value

  const q = search.value.toLowerCase()

  return items.value.filter(item =>
    item.title.toLowerCase().includes(q) ||
    item.location?.toLowerCase().includes(q) ||
    item.category?.toLowerCase().includes(q)
  )
})
</script>


<template>
    <div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-4 g-3">
      <div class="col-md">
        <h1 class="fw-bold">Explore Movies</h1>
        <p class="text-muted mb-0">blablalbalbeadfvbf vfqdwe nb movie </p>
      </div>

      <!-- Search -->
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
              :src="item.poster"
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