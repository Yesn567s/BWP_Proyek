<template>
  <div class="admin-shell">
    <div class="admin-layout">
      <Sidebar
        :model-value="activeLabel"
        :menu-items="navigation"
        @select="handleSelect"
      />

      <main class="admin-main">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter, RouterView } from 'vue-router'
import Sidebar from '../Sidebar.vue'
import navigation from '../config/navigation.json'

const route = useRoute()
const router = useRouter()

const activeLabel = computed(() => {
  const match = navigation.find((item) => item.routeName === route.name)
  return match?.name ?? 'Dashboard'
})

function handleSelect(item) {
  if (item.routeName && item.routeName !== route.name) {
    router.push({ name: item.routeName })
  }
}
</script>

<style>
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
    position: relative;
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

.movie-poster {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

@media (max-width: 992px) {
    .admin-layout {
        flex-direction: column;
    }

    .admin-main {
        padding: 2rem;
    }
}

@media (max-width: 576px) {
    .admin-main {
        padding: 1.5rem 1rem;
    }
}
</style>
