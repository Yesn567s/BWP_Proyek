<template>
  <section class="admin-section">
    <header class="section-header d-flex justify-content-between align-items-center">
      <div>
        <p class="eyebrow">Partners</p>
        <h1 class="mb-1">Cinema Network</h1>
        <p class="text-muted mb-0">Manage your partner cinemas.</p>
      </div>
      <router-link :to="{ name: 'adminNewVenue' }">
        <button class="admin-pill-btn btn btn-primary">+ New Venue</button>
      </router-link>
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
          <router-link
  :to="{ name: 'adminCinemaPage', params: { id: c.venue_id } }"
  class="stretched-link"
>

            <span class="admin-pill-btn small ghost">Manage</span>
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const cinemaPartners = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/cinema-partners')
    cinemaPartners.value = res.data
    console.log('cinemaPartners loaded', res.data)
  } catch (err) {
    console.error('Failed to load cinema partners', err)
    cinemaPartners.value = []
  }
})
</script>
