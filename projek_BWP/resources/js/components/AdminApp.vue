<script setup>
import { ref } from 'vue'

const activeTab = ref('Dashboard')

const menuItems = [
    { name: 'Dashboard', icon: '🏠' },
    { name: 'Movie Catalog', icon: '🎬' },
    { name: 'Cinema Catalog', icon: '🎟️' },
    { name: 'Food Catalog', icon: '🍿' },
    { name: 'Blogs Lists', icon: '📝' },
    { name: 'Transaction Log', icon: '📈' }
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
    <div class="admin-shell">
        <div class="admin-layout">
            <aside class="admin-sidebar">
                <div class="admin-brand">
                    <div class="brand-icon">💎</div>
                    <div>
                        <p class="brand-label mb-1">Intelligence</p>
                        <small>Admin Portal</small>
                    </div>
                </div>

                <nav class="admin-menu">
                    <button
                        v-for="item in menuItems"
                        :key="item.name"
                        class="admin-menu-btn"
                        :class="{ active: activeTab === item.name }"
                        @click="activeTab = item.name"
                    >
                        <span class="menu-icon">{{ item.icon }}</span>
                        <span class="menu-label">{{ item.name }}</span>
                    </button>
                </nav>

                <div class="admin-sidebar-footer">
                    <p class="sidebar-tip">Need help? Our concierge is on standby.</p>
                    <button class="admin-pill-btn danger w-100">Sign Out</button>
                </div>
            </aside>

            <main class="admin-main">

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
                                        v-for="(movie, i) in movies"
                                        :key="movie.id"
                                        v-if="i < 4"
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
                        <button class="admin-pill-btn">+ New Movie</button>
                    </header>

                    <div class="row g-4">
                        <div v-for="movie in movies" :key="movie.id" class="col-md-6">
                            <div class="panel-card catalog-card h-100">
                                <div class="d-flex gap-3 align-items-center">
                                    <img :src="movie.posterUrl" alt="Poster" style="width: 80px; height: 120px; object-fit: cover;" />
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
                        <div v-for="(c, i) in cinemaPartners" :key="i" class="col-md-6 col-lg-4">
                            <div class="panel-card partner-card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ c.name }}</h5>
                                        <p class="text-muted small mb-0">{{ c.city }}</p>
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

            </main>
        </div>
    </div>
</template>

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

.admin-sidebar {
    width: 290px;
    background: linear-gradient(180deg, #ffe086 0%, #f9c553 45%, #e8a735 100%);
    padding: 2.5rem 1.8rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    position: relative;
    z-index: 1;
    border-right: 1px solid rgba(255, 255, 255, 0.25);
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.brand-icon {
    width: 46px;
    height: 46px;
    border-radius: 16px;
    background: linear-gradient(135deg, #ffd86f, #f5b942);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 15px 30px rgba(245, 185, 66, 0.45);
}


.brand-label {
    margin: 0;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-weight: 700;
    color: rgba(54, 33, 7, 0.7);
}

.admin-brand small {
    font-weight: 600;
    color: #7a4d03;
}

.admin-menu {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.admin-menu-btn {
    border: none;
    border-radius: 0;
    background: transparent;
    padding: 0.95rem 0.75rem;
    border-radius: 5px;
    font-weight: 600;
    color: rgba(44, 28, 6, 0.7);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    width: 100%;
    border-left: 3px solid transparent;
    justify-content: flex-start;
}

.admin-menu-btn:hover {
    color: rgba(44, 28, 6, 0.95);
    background: rgba(255, 255, 255, 0.35);
}

.admin-menu-btn.active {
    color: #2d1b05;
    border-left-color: rgba(44, 28, 6, 0.9);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
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
</style>
