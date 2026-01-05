<template>
  <div class="container my-5 animate-fadeIn" style="max-width: 900px;">
    <div class="d-flex align-items-center gap-3 mb-4">
      <button class="btn btn-light rounded-circle p-3" @click="goBack">
        ←
      </button>

      <div>
        <h2 class="fw-bold mb-0">Create New Entry</h2>
        <small class="text-muted fw-semibold">
          Add a new movie to the active catalog
        </small>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="card shadow-lg border-0 p-5 px-6">
      <div class="row g-5">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Movie Title</label>
            <input v-model="movie.title" type="text" class="form-control form-control-lg" placeholder="e.g. Sonic the Hedgehog 3" required />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Genre</label>
            <input v-model="movie.genre" type="text" class="form-control form-control-lg" placeholder="e.g. Action, Adventure" required />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Description</label>
            <input v-model="movie.description" type="text" class="form-control form-control-lg" placeholder="Description of the Movie" required />
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Age Rating</label>
              <select v-model="movie.ageRating" class="form-select form-select-lg">
                <option>SU</option>
                <option>13+</option>
                <option>17+</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Price</label>
              <input v-model="movie.price" type="number" class="form-control form-control-lg" placeholder="45000" required step="5000" />
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Movie Duration</label>
              <input v-model="movie.durationMinutes" type="number" class="form-control form-control-lg" placeholder="120 minutes" required step="15" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Release Date</label>
              <input v-model="movie.releaseDate" type="date" class="form-control form-control-lg" required />
            </div>

            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Main di Bioskop Brp Hari</label>
              <input v-model="movie.playingTime" type="number" class="form-control form-control-lg" placeholder="30 days" required />
            </div>
          </div>

          <!-- Venue & Studio Selection -->
          <!-- SELECT VENUE & STUDIOS -->
<div class="mt-5">
  <h6 class="fw-bold text-uppercase mb-3">Select Venue & Studios</h6>

  <div class="row g-4">
    <!-- LEFT: VENUES -->
    <div class="col-md-5">
      <small class="text-primary fw-bold">1. Choose Venues</small>

      <div class="venue-list mt-3">
        <label
          v-for="venue in movieVenues"
          :key="venue.id"
          class="venue-card d-flex align-items-center gap-3"
          :class="{ active: selectedVenueId === venue.id }"
        >
          <input
            type="checkbox"
            class="form-check-input"
            :checked="selectedVenueId === venue.id"
            @change="selectVenue(venue)"
          />


          <div>
            <div class="fw-bold">{{ venue.name }}</div>
            <small class="text-muted text-uppercase">{{ venue.city }}</small>
          </div>
        </label>
      </div>
    </div>

    <!-- RIGHT: STUDIOS -->
    <div class="col-md-7">
      <small class="text-primary fw-bold">2. Assign Studios</small>

      <div class="studio-box mt-3">
        <small
          v-if="selectedVenue"
          class="text-muted fw-semibold d-block mb-2"
        >
          {{ selectedVenue.name }}
        </small>

        <div class="d-flex flex-wrap gap-2">
          <button
            v-for="studio in studios"
            :key="studio.id"
            class="btn studio-pill"
            :class="{ selected: selectedStudioIds.includes(studio.id) }"
            :disabled="!selectedVenue"
            @click.prevent="toggleStudio(studio.id)"
          >
            {{ studio.name }}
          </button>
          <small
            v-if="!selectedVenue"
            class="text-muted"
          >
            Select a venue to assign studios
          </small>
        </div>
      </div>
    </div>
  </div>
</div>



          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Poster Image</label>
            <input class="form-control" type="file" name="file" accept="image/*" @change="handleFileChange" />
          </div>
        </div>

        <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
          <div class="border rounded-4 p-3 bg-light" style="width: 200px; height: 300px;">
            <img v-if="posterPreview" :src="posterPreview" class="img-fluid h-100 w-100 object-fit-cover rounded-3" />
            <div v-else class="h-100 d-flex align-items-center justify-content-center text-muted small fw-semibold">
              Poster Preview
            </div>
          </div>

          <small class="text-muted mt-3 text-center">
            The poster updates automatically when a file is chosen
          </small>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
        <button type="button" class="btn btn-link text-secondary fw-bold" @click="goBack">
          DISCARD
        </button>

        <button type="submit" class="btn btn-primary px-5 fw-bold">
          PUBLISH MOVIE
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const movie = reactive({
  title: '',
  genre: '',
  description: '',
  releaseDate: '',
  playingTime: 7,
  ageRating: '13+',
  price: 45000,
  durationMinutes: 120
})

const venues = ref([])

const selectedVenueIds = ref([])
const selectedVenueId = ref(null)
const selectedStudioIds = ref([])

const posterFile = ref(null)
const posterPreview = ref(null)

const selectVenue = (venue) => {
  selectedVenueId.value =
    selectedVenueId.value === venue.id ? null : venue.id

  selectedStudioIds.value = []
}

const selectedVenue = computed(() => {
  return movieVenues.value.find(v => v.id === selectedVenueId.value)
})

const studios = computed(() => {
  return selectedVenue.value?.studios ?? []
})

const toggleStudio = (studioId) => {
  if (selectedStudioIds.value.includes(studioId)) {
    selectedStudioIds.value =
      selectedStudioIds.value.filter(id => id !== studioId)
  } else {
    selectedStudioIds.value.push(studioId)
  }
}


onMounted(async () => {
  const res = await axios.get('/api/admin/venues-with-studios')
  venues.value = res.data
})

const movieVenues = computed(() => {
  return venues.value.filter(v => v.venue_type === 'Cinema')
})

const onVenueChange = () => {
  // Allow only ONE venue
  if (selectedVenueIds.value.length > 1) {
    selectedVenueIds.value = [selectedVenueIds.value.at(-1)]
  }

  // Remove studios not belonging to selected venue
  const allowedStudioIds = venues.value
    .filter(v => selectedVenueIds.value.includes(v.id))
    .flatMap(v => v.studios.map(s => s.id))

  selectedStudioIds.value = selectedStudioIds.value.filter(id =>
    allowedStudioIds.includes(id)
  )
}

const isVenueSelected = (venueId) => {
  return selectedVenueIds.value.includes(venueId)
}

const isAllStudiosSelected = (venue) => {
  const studioIds = venue.studios.map(s => s.id)
  return studioIds.every(id => selectedStudioIds.value.includes(id))
}

const allVenuesSelected = computed(() =>
  venues.value.length &&
  selectedVenueIds.value.length === venues.value.length
)

const toggleSelectAllVenues = () => {
  if (allVenuesSelected.value) {
    selectedVenueIds.value = []
    selectedStudioIds.value = []
  } else {
    // Still force ONE venue rule → select first venue only
    selectedVenueIds.value = [venues.value[0].id]
  }
}

const toggleSelectAllStudios = (venue) => {
  const studioIds = venue.studios.map(s => s.id)

  if (isAllStudiosSelected(venue)) {
    selectedStudioIds.value = selectedStudioIds.value.filter(
      id => !studioIds.includes(id)
    )
  } else {
    selectedStudioIds.value = [
      ...new Set([...selectedStudioIds.value, ...studioIds])
    ]
  }
}



const handleFileChange = (e) => {
  const file = e.target.files?.[0]
  if (!file) return

  posterFile.value = file
  posterPreview.value = URL.createObjectURL(file)
}

const goBack = () => {
  router.push({ name: 'adminMovieCatalog' })
}

const handleSubmit = async () => {
  if (!posterFile.value) {
    alert('Poster image is required')
    return
  }

  const formData = new FormData()
  formData.append('title', movie.title)
  formData.append('genre', movie.genre)
  formData.append('description', movie.description)
  formData.append('releaseDate', movie.releaseDate)
  formData.append('playingTime', movie.playingTime)
  formData.append('ageRating', movie.ageRating)
  formData.append('price', movie.price)
  formData.append('duration_minutes', movie.durationMinutes)
  formData.append('poster', posterFile.value)

  formData.append('venue_id', selectedVenueId.value)
  selectedStudioIds.value.forEach(id => {
    formData.append('studio_ids[]', id)
  })
  
  try {
    await axios.post('/api/admin/movies', formData)

    alert('Movie created successfully')
    goBack()
  } catch (error) {
    const res = error.response

    if (res?.status === 409 && res.data?.type === 'studio_full') {
      alert('⚠️ All studios are currently full.\nPlease create a new studio before adding this movie.')
    } else {
      alert('Failed to create movie')
    }
  }
}
</script>

<style scoped>
  /* Venue cards */
.venue-list {
  background: #f8f9fb;
  border-radius: 20px;
  padding: 18px;
  display: grid;
  row-gap: 12px;
}

.venue-card {
  background: #fff;
  border-radius: 16px;
  padding: 18px 20px;
  border: 1px solid transparent;
  cursor: pointer;
  transition: transform 0.12s ease, box-shadow 0.12s ease, border-color 0.12s ease;
  box-shadow: 0 1px 4px rgba(16,24,40,0.04);
  margin-bottom: 0;
  min-height: 64px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.venue-card .fw-bold {
  font-size: 16px;
  letter-spacing: 0.2px;
}

.venue-card small {
  color: #6c757d;
  text-transform: uppercase;
  font-size: 12px;
}

.venue-card:hover {
  transform: translateY(-3px);
  border-color: #cfe2ff;
  box-shadow: 0 6px 18px rgba(13,110,253,0.06);
}

.venue-card.active {
  border-color: #0d6efd;
  background: #f0f6ff;
  box-shadow: 0 8px 24px rgba(13,110,253,0.08);
}

/* Studio box */
.studio-box {
  background: #f8f9fb;
  border-radius: 20px;
  padding: 16px;
}

/* Studio pills */
.studio-pill {
  border-radius: 999px;
  padding: 6px 16px;
  font-weight: 600;
  font-size: 14px;
  background: #e9ecef;
  border: none;
  color: #6c757d;
}

.studio-pill.selected {
  background: #0d6efd;
  color: white;
}

</style>
