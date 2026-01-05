<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()

const venue = ref(null)

onMounted(async () => {
  try {
    console.log('adminCinemaPage route id:', route.params.id)
    const id = Number(route.params.id)
    if (!id) throw new Error('Invalid venue id')
    const res = await axios.get(`/api/venues/${id}`)
    venue.value = res.data
  } catch (err) {
    console.error('Failed to load venue', err)
    venue.value = null
  }
})

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
</script>


<template>
  <div class="container py-4" v-if="venue">

    <!-- HEADER -->
    <div class="mb-4">
      <h4 class="fw-bold mb-1">{{ venue.venue_name }}</h4>
      <small class="text-muted">
        {{ venue.location }}
      </small>
    </div>

    <div class="row g-4">
      <!-- LEFT SIDE -->
      <div class="col-lg-8">

        <!-- STUDIOS -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0">Studios & Rooms</h6>
              <button class="btn btn-primary btn-sm">
                + Add Studio
              </button>
            </div>

            <div class="row g-3">
              <div
                v-for="studio in venue.studios"
                :key="studio.studio_id"
                class="col-md-6"
              >
                <div class="border rounded p-3 h-100">
                  <div class="d-flex justify-content-between">
                    <div class="fw-semibold">
                      {{ studio.studio_name }}
                    </div>
                    <span class="badge bg-primary">
                      {{ studio.studio_type }}
                    </span>
                  </div>

                  <small class="text-muted d-block mt-1">
                    {{ studio.schedules.length }} schedules
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- AIRING SCHEDULE -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0">Airing Schedule</h6>
              <button class="btn btn-outline-primary btn-sm">
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
                class="mb-2"
              >
                <div class="fw-semibold">
                  {{ schedule.product?.product_name ?? 'Unknown Movie' }}
                </div>

                <small class="text-muted">
                  {{ studio.studio_name }} •
                  {{ formatTime(schedule.start_datetime) }} -
                  {{ formatTime(schedule.end_datetime) }}
                </small>
              </div>
            </div>

            <div v-if="totalSchedules === 0" class="text-muted text-center">
              No schedules available
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT SIDE -->
      <div class="col-lg-4">

        <!-- VENUE INFO -->
        <div class="card mb-3">
          <div class="card-body">
            <h6 class="fw-bold">Venue Info</h6>

            <p class="mb-1">
              <strong>Type:</strong> {{ venue.venue_type }}
            </p>
            <p class="mb-1">
              <strong>Location:</strong> {{ venue.location }}
            </p>

            <button class="btn btn-light w-100 mt-2">
              Update Information
            </button>
          </div>
        </div>

        <!-- SECURITY STATUS -->
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h6 class="fw-bold">Security Status</h6>
            <small>
              This venue is verified for high-density occupancy.
            </small>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div v-else class="text-center py-5">
    Loading venue data...
  </div>
</template>
