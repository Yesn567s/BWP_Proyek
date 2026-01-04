<template>
  <section class="admin-section">
    <header class="section-header">
      <p class="eyebrow">Realtime overview</p>
      <h1>Dashboard</h1>
      <p class="text-muted mb-0">Welcome back! Here's your performance overview.</p>
    </header>

    <div class="row g-4 mb-5">
      <div v-for="(stat, i) in stats" :key="i" class="col-sm-6 col-lg-3">
        <div class="panel-card stat-card h-100">
          <small class="text-uppercase fw-semibold">{{ stat.label }}</small>
          <h3 class="fw-bold mt-2 mb-2">{{ stat.value }}</h3>
          <span class="badge" :class="stat.trend.startsWith('+') ? 'bg-success' : 'bg-danger'">
            {{ stat.trend }}
          </span>
        </div>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <div class="col-lg-8">
        <div class="panel-card chart-card h-100">
          <h5 class="fw-bold mb-4">Traffic Analysis</h5>
          <svg viewBox="0 0 600 200" class="w-100">
            <defs>
              <linearGradient id="gradientStroke" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#0d6efd;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#0d6efd;stop-opacity:0" />
              </linearGradient>
            </defs>
            <path
              d="M0,180 C100,160 200,80 300,100 C400,120 500,40 600,60"
              fill="none"
              stroke="#0d6efd"
              stroke-width="3"
              stroke-linecap="round"
            />
            <circle cx="300" cy="100" r="5" fill="#0d6efd" />
          </svg>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="panel-card leaderboard-card h-100">
          <h5 class="fw-bold mb-4">🏆 Leaderboard</h5>
          <div class="d-flex flex-column gap-3">
            <div
              v-for="(movie, i) in topMovies"
              :key="movie.id || i"
              class="d-flex align-items-center gap-3 pb-3 border-bottom"
            >
              <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-weight: 700;">
                {{ i + 1 }}
              </div>
              <div>
                <strong class="d-block">{{ movie.title }}</strong>
                <small class="text-muted">{{ movie.genre }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'

const stats = [
  { label: 'Total Revenue', value: 'Rp 45.2M', trend: '+12.5%' },
  { label: 'Active Visitors', value: '12,840', trend: '+8.2%' },
  { label: 'Average Sales', value: 'Rp 320k', trend: '-2.4%' },
  { label: 'Ticket Units', value: '2,450', trend: '+14%' }
]

const movies = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/movies')
    movies.value = res.data
  } catch (err) {
    console.error('Failed to load movies', err)
    movies.value = []
  }
})

const topMovies = computed(() => movies.value ? movies.value.slice(0, 4) : [])
</script>
