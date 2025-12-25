<script setup>
import { ref } from 'vue'

const activeTab = ref('Dashboard')

const menuItems = [
  { name: 'Dashboard' },
  { name: 'Movie Catalog' },
  { name: 'Cinema Catalog' },
  { name: 'Food Catalog' },
  { name: 'Blogs Lists' },
  { name: 'Transaction Log' }
]

const stats = [
  { label: 'Total Revenue', value: 'Rp 45.2M', trend: '+12.5%' },
  { label: 'Active Visitors', value: '12,840', trend: '+8.2%' },
  { label: 'Average Sales', value: 'Rp 320k', trend: '-2.4%' },
  { label: 'Ticket Units', value: '2,450', trend: '+14%' }
]

const movies = [
  { id: 1, title: 'Inside Out 2', genre: 'Animation', posterUrl: 'https://via.placeholder.com/80x120' },
  { id: 2, title: 'Dune Part Two', genre: 'Sci-Fi', posterUrl: 'https://via.placeholder.com/80x120' }
]

const cinemaPartners = [
  { name: 'XXI Premium', city: 'Surakarta' },
  { name: 'CGV Starlight', city: 'Atlantis' },
  { name: 'Cineplex Max', city: 'Surabaya' }
]
</script>

<template>
    <!-- <h1>haloh this is admin page</h1> -->

    <div class="d-flex min-vh-100 bg-light">

        <!-- SIDEBAR -->
        <aside class="bg-white border-end p-4" style="width:280px">
        <div class="d-flex align-items-center mb-4">
            <div class="fs-4 me-2">💎</div>
            <div>
            <div class="fw-bold text-uppercase">Intelligence</div>
            <small class="text-primary">Admin Portal</small>
            </div>
        </div>

        <button
            v-for="item in menuItems"
            :key="item.name"
            class="btn w-100 text-start mb-2"
            :class="activeTab === item.name ? 'btn-primary' : 'btn-outline-secondary'"
            @click="activeTab = item.name"
        >
            {{ item.name }}
        </button>

        <div class="mt-auto pt-4 border-top">
            <button class="btn btn-outline-danger w-100">
            Sign Out
            </button>
        </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-grow-1 p-4 overflow-auto">

        <!-- DASHBOARD -->
        <div v-if="activeTab === 'Dashboard'">
            <div class="row g-4 mb-4">
            <div v-for="(stat,i) in stats" :key="i" class="col-md-6 col-lg-3">
                <div class="card shadow-sm">
                <div class="card-body">
                    <small class="text-muted">{{ stat.label }}</small>
                    <h4 class="fw-bold">{{ stat.value }}</h4>
                    <span :class="stat.trend.startsWith('+') ? 'text-success' : 'text-danger'">
                    {{ stat.trend }}
                    </span>
                </div>
                </div>
            </div>
            </div>

            <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4">
                <h5 class="fw-bold">Traffic Analysis</h5>
                <svg viewBox="0 0 600 200" class="w-100">
                    <path d="M0,180 C100,160 200,80 300,100 C400,120 500,40 600,60"
                        fill="none" stroke="#0d6efd" stroke-width="4"/>
                </svg>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4">
                <h5 class="fw-bold mb-3">Leaderboard</h5>
                <div v-for="(movie,i) in movies" :key="movie.id" class="mb-2">
                    <strong>{{ i+1 }}.</strong> {{ movie.title }}
                </div>
                </div>
            </div>
            </div>
        </div>

        <!-- MOVIE CATALOG -->
        <div v-if="activeTab === 'Movie Catalog'">
            <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold">Movie Catalog</h3>
                <small class="text-muted">Total movies: {{ movies.length }}</small>
            </div>
            <button class="btn btn-primary">+ New Movie</button>
            </header>

            <div class="row g-4">
            <div v-for="movie in movies" :key="movie.id" class="col-md-6">
                <div class="card p-3 d-flex flex-row gap-3">
                <img :src="movie.posterUrl" style="width:80px;height:120px;object-fit:cover">
                <div>
                    <h5 class="fw-bold">{{ movie.title }}</h5>
                    <small class="text-muted">{{ movie.genre }}</small>
                    <div class="mt-2">
                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <!-- CINEMA CATALOG -->
        <div v-if="activeTab === 'Cinema Catalog'">
            <header class="mb-4">
            <h3 class="fw-bold">Cinema Network</h3>
            </header>

            <div class="row g-4">
            <div v-for="(c,i) in cinemaPartners" :key="i" class="col-md-4">
                <div class="card p-4">
                <h5 class="fw-bold">{{ c.name }}</h5>
                <small class="text-muted">{{ c.city }}</small>
                </div>
            </div>
            </div>
        </div>

        <!-- PLACEHOLDERS -->
        <div
            v-if="['Food Catalog','Blogs Lists','Transaction Log'].includes(activeTab)"
            class="text-center py-5"
        >
            <h4 class="fw-bold">{{ activeTab }}</h4>
            <p class="text-muted">Modular workspace</p>
        </div>

        </main>
    </div>
</template>
