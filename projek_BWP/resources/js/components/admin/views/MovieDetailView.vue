<template>
  <section class="admin-section">
    <header class="section-header d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div>
        <p class="eyebrow">Movie</p>
        <h1 class="mb-1">{{ movie?.title || 'Movie Details' }}</h1>
        <p class="text-muted mb-0">Quick peek at the selected title.</p>
      </div>
      <button class="admin-pill-btn small ghost" @click="goBack">← Back</button>
    </header>

    <div v-if="loading" class="text-muted">Loading...</div>
    <div v-else-if="!movie" class="text-danger">Movie not found.</div>

    <div v-else class="panel-card catalog-card">
      <div class="d-flex gap-4 align-items-start flex-wrap">
        <img :src="movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Poster" />
        <div class="details flex-1">
          <h4 class="fw-bold mb-2">{{ movie.title }}</h4>
          <p class="text-muted mb-2">{{ movie.genre || 'Genre unavailable' }}</p>
          <p class="mb-3">{{ movie.desc || 'No description provided.' }}</p>
          <div class="d-flex gap-3 flex-wrap align-items-center">
            <span class="badge bg-primary">ID: {{ movie.id }}</span>
            <span class="badge bg-secondary">Price: {{ formatPrice(movie.price) }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const movie = ref(null)
const loading = ref(true)

const goBack = () => {
  router.push({ name: 'adminMovieCatalog' })
}

const formatPrice = (price) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(price || 0)

onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/movies')
    const list = res.data || []
    movie.value = list.find((m) => String(m.id) === String(route.params.id)) || null
  } catch (err) {
    console.error('Failed to load movies', err)
    movie.value = null
  } finally {
    loading.value = false
  }
})
</script>
