<style scoped>
.cinema-page {
  background: #f6f8fc;
}

.soft-card {
  border-radius: 18px;
  border: none;
}

.studio-card {
  background: #f9fbff;
  border-radius: 14px;
}

.schedule-row {
  background: #f9fbff;
  border-radius: 14px;
}

.time-pill {
  padding: 4px 10px;
  border-radius: 8px;
  background: white;
  border: 1px solid #e2e6f0;
  font-size: 0.8rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
  margin-bottom: 8px;
}

.security-card {
  background: linear-gradient(135deg, #5b5ce2, #4c4ddc);
  color: white;
  border-radius: 20px;
  padding: 20px;
}
</style>


<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import AddStudioModal from './ModalAddStudio.vue'

const route = useRoute()
const router = useRouter()

const showAddStudioModal = ref(false)
const venue = ref(null)

const goBack = () => {
  router.back()
}

const fetchVenue = async () => {
  const res = await axios.get(`/api/venues/${route.params.id}`)
  venue.value = res.data
}

onMounted(fetchVenue)

const totalSchedules = computed(() => {
  return venue.value
    ? venue.value.studios.reduce(
        (total, studio) => total + studio.schedules.length,
        0
      )
    : 0
})

const formatTime = (datetime) => {
  return new Date(datetime).toLocaleTimeString([], {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const groupedSchedules = computed(() => {
  if (!venue.value) return []

  const map = {}

  venue.value.studios.forEach(studio => {
    studio.schedules.forEach(schedule => {
      const movie = schedule.product
      if (!movie) return

      const dateKey = new Date(schedule.start_datetime)
        .toISOString()
        .slice(0, 10) // YYYY-MM-DD

      // Movie
      if (!map[movie.product_id]) {
        map[movie.product_id] = {
          movie,
          dates: {}
        }
      }

      // Date
      if (!map[movie.product_id].dates[dateKey]) {
        map[movie.product_id].dates[dateKey] = {}
      }

      // Studio
      if (!map[movie.product_id].dates[dateKey][studio.studio_name]) {
        map[movie.product_id].dates[dateKey][studio.studio_name] = []
      }

      map[movie.product_id].dates[dateKey][studio.studio_name].push(schedule)
    })
  })

  return Object.values(map)
})

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}


</script>



<template>
  <div v-if="venue" class="container py-4 cinema-page">

    <!-- HEADER -->
    <div class="d-flex align-items-center gap-3 mb-4">
      <button class="btn btn-light rounded-circle" @click="goBack">
        ←
      </button>
      <div>
        <h4 class="fw-bold mb-0">{{ venue.venue_name }}</h4>
        <small class="text-muted">
          {{ venue.location }}
        </small>
      </div>
    </div>

    <div class="row g-4">

      <!-- LEFT -->
      <div class="col-lg-8">

        <!-- STUDIOS -->
        <div class="card soft-card mb-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold mb-1">Studios & Rooms</h6>
                <small class="text-muted">
                  Total studios: {{ venue.studios.length }}
                </small>
              </div>
              <button class="btn btn-primary btn-sm rounded-pill" @click="showAddStudioModal = true">
                + Add Studio
              </button>
            </div>

            <div class="row g-3">
              <div
                v-for="studio in venue.studios"
                :key="studio.studio_id"
                class="col-md-6"
              >
                <div class="studio-card p-3 h-100">
                  <div class="d-flex justify-content-between">
                    <div>
                      <div class="fw-semibold">{{ studio.studio_name }}</div>
                      <span class="badge bg-light text-primary mt-1">
                        {{ studio.studio_type }}
                      </span>
                    </div>

                    <div class="text-end small text-muted">
                      {{ studio.schedules.length }} schedules
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- AIRING SCHEDULE -->
        <!-- <div class="card soft-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0">Airing Schedule</h6>
              <button class="btn btn-outline-primary btn-sm rounded-pill">
                Edit Schedule
              </button>
            </div>

            <div
              v-for="studio in venue.studios"
              :key="studio.studio_id"
              class="mb-4"
            >
              <div
                v-for="schedule in studio.schedules"
                :key="schedule.schedule_id"
                class="schedule-row p-3 mb-2"
              >
                <div class="fw-semibold">
                  {{ schedule.product?.name ?? 'Unknown Movie' }}
                </div>

                <small class="text-muted d-block mb-2">
                  {{ studio.studio_name }}
                </small>

                <div class="d-flex gap-2">
                  <span class="time-pill">
                    {{ formatTime(schedule.start_datetime) }}
                  </span>
                  <span class="time-pill">
                    {{ formatTime(schedule.end_datetime) }}
                  </span>
                </div>
              </div>
            </div>

            <div
              v-if="totalSchedules === 0"
              class="text-muted text-center py-4"
            >
              No schedules available
            </div>
          </div>
        </div> -->

        <div
          v-for="item in groupedSchedules"
          :key="item.movie.product_id"
          class="mb-4"
        >
          <!-- MOVIE -->
          <h6 class="fw-bold mb-2">
            🎬 {{ item.movie.name }}
          </h6>

          <!-- DATE -->
          <div
            v-for="(studios, date) in item.dates"
            :key="date"
            class="mb-3 ps-2"
          >
            <div class="fw-semibold text-muted mb-1">
              📅 {{ formatDate(date) }}
            </div>

            <!-- STUDIO -->
            <div
              v-for="(schedules, studioName) in studios"
              :key="studioName"
              class="mb-2 ps-3"
            >
              <small class="text-muted">{{ studioName }}</small>

              <div class="d-flex flex-wrap gap-2 mt-1">
                <span
                  v-for="schedule in schedules"
                  :key="schedule.schedule_id"
                  class="time-pill"
                >
                  {{ formatTime(schedule.start_datetime) }}
                </span>
              </div>
            </div>
          </div>
        </div>



      </div>

      <!-- RIGHT -->
      <div class="col-lg-4">

        <!-- VENUE INFO -->
        <div class="card soft-card mb-3">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Venue Info</h6>

            <div class="info-row">
              <span>Total Studios</span>
              <strong>{{ venue.studios.length }} Registered</strong>
            </div>

            <div class="info-row">
              <span>Location Type</span>
              <strong>Premium Cinema Mall</strong>
            </div>

            <!-- <button class="btn btn-light w-100 mt-3 rounded-pill">
              Update Information
            </button> -->
          </div>
        </div>

        <!-- SECURITY -->
        <!-- <div class="security-card">
          <h6 class="fw-bold">Security Status</h6>
          <small>
            This venue is verified for high-density occupancy.
            <br />
            Last inspection: Dec 20, 2024
          </small>
        </div> -->

      </div>
    </div>
  </div>

  <div v-else class="text-center py-5">
    Loading venue data...
  </div>

  <AddStudioModal
    v-if="showAddStudioModal"
    :venue-id="venue.venue_id"
    @close="showAddStudioModal = false"
    @saved="fetchVenue"
  />


</template>

