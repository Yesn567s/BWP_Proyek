<template>
  <section class="admin-section">
    <header class="section-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
      <div>
        <p class="eyebrow">Library</p>
        <h1 class="mb-1">Movie Catalog</h1>
        <p class="text-muted mb-0">Total movies: <strong>{{ movies.length }}</strong></p>
      </div>
      <router-link :to="{ name: 'adminNewMovies' }">
        <button class="admin-pill-btn btn btn-primary">+ New Movie</button>
      </router-link>
    </header>

    <div class="row g-4">
      <div v-for="movie in movies" :key="movie.id" class="col-md-6">
        <div class="panel-card catalog-card h-100">
          <div class="d-flex gap-3 align-items-center">
            <img :src="movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Movie Poster" />
            <div class="details">
              <h5 class="fw-bold mb-1">{{ movie.title }}</h5>
              <p class="text-muted mb-3">{{ movie.genre }}</p>
              <button type="button" class="btn btn-primary" @click="openMovie(movie)">View Details</button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const movies = ref([])
const router = useRouter()

onMounted(async () => {
  try {
    const res = await axios.get('/api/movies')
    movies.value = res.data
  } catch (err) {
    console.error('Failed to load movies', err)
    movies.value = []
  }
})

const openMovie = (movie) => {
  router.push({ name: 'adminMovieDetail', params: { id: movie.id } })
}
</script>
