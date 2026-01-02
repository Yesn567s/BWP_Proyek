<style scoped>
.admin-shell {
    min-height: 100vh;
    background: linear-gradient(180deg, #f7fbff 0%, #ffffff 45%, #fef9f2 100%);
    color: #1f2a37;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, sans-serif;
}

.admin-layout {
    display: flex;
    min-height: 100vh;
}









.menu-icon {
    width: 32px;
    height: 32px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
}



.menu-label {
    flex: 1;
    text-align: left;
}

.admin-sidebar-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.sidebar-tip {
    font-size: 0.85rem;
    color: rgba(44, 28, 6, 0.65);
    margin: 0;
}

.admin-pill-btn {
    border: none;
    border-radius: 999px;
    background: linear-gradient(135deg, #ffd86f 0%, #f5b942 100%);
    color: #1f1f1f;
    font-weight: 700;
    padding: 0.65rem 1.5rem;
    letter-spacing: 0.01em;
    box-shadow: 0 15px 35px rgba(245, 185, 66, 0.45);
    transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
}

.admin-pill-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.05);
    box-shadow: 0 20px 40px rgba(245, 185, 66, 0.5);
}

.admin-pill-btn.small {
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
}

.admin-pill-btn.ghost {
    background: rgba(13, 110, 253, 0.08);
    color: #0d47a1;
    box-shadow: none;
}

.admin-pill-btn.ghost:hover {
    box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
}

.admin-pill-btn.danger {
    background: linear-gradient(135deg, #ff7b7b 0%, #f54b64 100%);
    color: #ffffff;
    box-shadow: 0 18px 35px rgba(245, 75, 100, 0.35);
}

.admin-main {
    flex: 1;
    padding: 3rem;
    background: linear-gradient(145deg, rgba(13, 110, 253, 0.04), rgba(13, 202, 240, 0.04)) #f7fbff;
    overflow-y: auto;
}

.admin-section {
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h1 {
    font-size: 2.25rem;
    font-weight: 800;
}

.section-header .eyebrow {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.2em;
    color: #6c7a89;
    font-weight: 700;
    margin-bottom: 0.35rem;
}

.panel-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    box-shadow: 0 18px 35px rgba(15, 23, 42, 0.06);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    padding: 1.5rem;
}

.panel-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 22px 45px rgba(15, 23, 42, 0.08);
}

.stat-card {
    background: linear-gradient(160deg, rgba(13, 110, 253, 0.08), rgba(13, 202, 240, 0.05));
    border: none;
}

.chart-card,
.leaderboard-card {
    padding: 2rem;
}

.catalog-card img {
    border-radius: 14px;
}

.catalog-card .details {
    flex: 1;
}

.partner-card {
    background: linear-gradient(160deg, rgba(13, 110, 253, 0.05), rgba(255, 216, 111, 0.08));
}

.placeholder-section {
    opacity: 0.75;
}

@media (max-width: 992px) {
    .admin-layout {
        flex-direction: column;
    }

    .admin-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .admin-sidebar-footer {
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .admin-menu {
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .admin-main {
        padding: 2rem 1.25rem;
    }

    .admin-sidebar {
        padding: 1.5rem;
    }

    .admin-menu-btn {
        width: 100%;
    }
}

.movie-poster {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}
</style>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Sidebar from './Sidebar.vue'

const activeTab = ref('Dashboard')
const route = useRoute()
const router = useRouter()
const showDefault = computed(() => !route.name || ['adminDashboard', 'adminMovieCatalog', 'adminCinemaCatalog', 'adminFoodCatalog', 'adminBlogList', 'adminTransactionLog'].includes(route.name))

// keep activeTab in sync with admin routes
watch(() => route.name, (name) => {
  if (name === 'adminDashboard') {
    activeTab.value = 'Dashboard'
  } else if (name === 'adminNewMovies') {
    activeTab.value = 'Movie Catalog'
  } else if (name === 'adminMovieCatalog') {
    activeTab.value = 'Movie Catalog'
  } else if (name === 'adminCinemaCatalog') {
    activeTab.value = 'Cinema Catalog'
  } else if (name === 'adminFoodCatalog') {
    activeTab.value = 'Food Catalog'
  } else if (name === 'adminBlogList') {
    activeTab.value = 'Blogs Lists'
  } else if (name === 'adminTransactionLog') {
    activeTab.value = 'Transaction Log'
  }
})

function onSidebarSelect(item) {
  // if menu item has a routeName, navigate; otherwise just set tab
  if (item.routeName) {
    router.push({ name: item.routeName })
  } else {
    activeTab.value = item.name
  }
}


const stats = [
  { label: 'Total Revenue', value: 'Rp 45.2M', trend: '+12.5%' },
  { label: 'Active Visitors', value: '12,840', trend: '+8.2%' },
  { label: 'Average Sales', value: 'Rp 320k', trend: '-2.4%' },
  { label: 'Ticket Units', value: '2,450', trend: '+14%' }
]

const movies = ref([])

onMounted(() => {
  axios.get('/api/movies').then(res => {
    console.log(res.data[0])
    movies.value = res.data
  })
})

const topMovies = computed(() => movies.value ? movies.value.slice(0, 4) : [])

const cinemaPartners = ref([])

onMounted(async () => {
    try {
        const res = await axios.get('/api/cinema-partners')
        cinemaPartners.value = res.data
    } catch (err) {
        console.error('Failed to load cinema partners', err)
        cinemaPartners.value = []
    }
})

const menuItems = [
  { name: 'Dashboard', icon: '🏠', routeName: 'adminDashboard' },
  { name: 'Movie Catalog', icon: '🎬', routeName: 'adminMovieCatalog' },
  { name: 'Cinema Catalog', icon: '🎟️', routeName: 'adminCinemaCatalog' },
  { name: 'Food Catalog', icon: '🍿', routeName: 'adminFoodCatalog' },
  { name: 'Blogs Lists', icon: '📝', routeName: 'adminBlogList' },
  { name: 'Transaction Log', icon: '📈', routeName: 'adminTransactionLog' }
]
</script>

<template>
    <div class="admin-shell">
        <div class="admin-layout">
        <Sidebar v-model="activeTab" :menu-items="menuItems" @select="onSidebarSelect" />

            <main class="admin-main">
              <router-view />

                <div v-if="showDefault">
                <section v-if="activeTab === 'Dashboard'" class="admin-section">
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

                <section v-if="activeTab === 'Movie Catalog'" class="admin-section">
                    <header class="section-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
                        <div>
                            <p class="eyebrow">Library</p>
                            <h1 class="mb-1">Movie Catalog</h1>
                            <p class="text-muted mb-0">Total movies: <strong>{{ movies.length }}</strong></p>
                        </div>
                        <router-link :to="{ name: 'adminNewMovies' }">
                            <button class="admin-pill-btn">+ New Movie</button>
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
                                        <button class="admin-pill-btn small ghost">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section v-if="activeTab === 'Cinema Catalog'" class="admin-section">
                    <header class="section-header">
                        <p class="eyebrow">Partners</p>
                        <h1 class="mb-1">Cinema Network</h1>
                        <p class="text-muted mb-0">Manage your partner cinemas.</p>
                    </header>

                    <div class="row g-4">
                        <div v-for="(c, i) in cinemaPartners" :key="c.venue_id || i" class="col-md-6 col-lg-4">
                            <div class="panel-card partner-card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ c.venue_name }}</h5>
                                        <p class="text-muted small mb-0">{{ c.location }}</p>
                                    </div>
                                    <span class="badge bg-primary">Active</span>
                                </div>
                                <button class="admin-pill-btn small ghost">Manage</button>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    v-if="['Food Catalog','Blogs Lists','Transaction Log'].includes(activeTab)"
                    class="admin-section placeholder-section text-center py-5"
                >
                    <h4 class="fw-bold mb-2">{{ activeTab }}</h4>
                    <p class="text-muted mb-0">Coming soon – modular workspace.</p>
                </section>

                </div>

            </main>
        </div>
    </div>
</template>


