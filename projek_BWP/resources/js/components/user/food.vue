<style scoped>
.cat-icon {
  width: 22px;
  height: 22px;
  object-fit: contain;
  margin-right: 6px;
}

.category-scroll {
  scrollbar-width: none;
}
.category-scroll::-webkit-scrollbar {
  display: none;
}

.ticket-card {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.ticket-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.card-img-top {
  transition: transform 0.5s ease;
}
.ticket-card:hover .card-img-top {
  transform: scale(1.05);
}

.search-input {
  padding-left: 3rem;
}

.list-group-item {
  padding: 1.25rem 1.5rem;
}
</style>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const venues = ref([])

onMounted(async () => {
  const res = await axios.get('/api/food/venues')
  venues.value = res.data
})
</script>

<template>
<div class="container py-5">

  <h2 class="fw-bold mb-4">Pilih Venue</h2>

  <div class="list-group">
    <button
      v-for="v in venues"
      :key="v.venue_id"
      class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-4 mb-3 shadow-sm"
      @click="router.push({ name: 'food-menu', params: { venueId: v.venue_id } })"
    >
      <div>
        <h5 class="fw-bold mb-0">{{ v.venue_name }}</h5>
        <small class="text-muted">{{ v.location }}</small>
      </div>
      <i class="bi bi-chevron-right fs-4"></i>
    </button>
  </div>

</div>

</template>

