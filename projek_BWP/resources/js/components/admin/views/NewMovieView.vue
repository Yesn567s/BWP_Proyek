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

    <form @submit.prevent="handleSubmit" class="card shadow-lg border-0 p-5">
      <!-- ROW 1: Inputs + Poster Preview (2 columns) -->
      <div class="row g-4">
        <!-- LEFT: Form Inputs -->
        <div class="col-md-7">
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
            <textarea v-model="movie.description" class="form-control" rows="3" placeholder="Description of the Movie" required></textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-4">
              <label class="form-label fw-semibold text-uppercase small">Age Rating</label>
              <select v-model="movie.ageRating" class="form-select">
                <option>SU</option>
                <option>13+</option>
                <option>17+</option>
              </select>
            </div>

            <div class="col-4">
              <label class="form-label fw-semibold text-uppercase small">Price</label>
              <input v-model="movie.price" type="number" class="form-control" placeholder="45000" required step="5000" />
            </div>

            <div class="col-4">
              <label class="form-label fw-semibold text-uppercase small">Duration (min)</label>
              <input v-model="movie.durationMinutes" type="number" class="form-control" placeholder="120" required step="15" />
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Release Date</label>
              <input v-model="movie.releaseDate" type="date" class="form-control" required />
            </div>

            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Playing Days</label>
              <input v-model="movie.playingTime" type="number" class="form-control" placeholder="30 days" required />
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fw-semibold text-uppercase small">Poster Image</label>
            <input class="form-control" type="file" name="file" accept="image/*" @change="handleFileChange" />
          </div>
        </div>

        <!-- RIGHT: Poster Preview -->
        <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
          <div class="poster-preview-box">
            <img v-if="posterPreview" :src="posterPreview" class="img-fluid h-100 w-100 object-fit-cover rounded-3" />
            <div v-else class="poster-placeholder">
              <i class="bi bi-image fs-1 mb-2"></i>
              <span>Poster Preview</span>
            </div>
          </div>
          <small class="text-muted mt-3 text-center">
            Updates automatically when a file is chosen
          </small>
        </div>
      </div>

      <!-- ROW 2: Venue & Studios (Full Width) -->
      <div class="venue-studio-section mt-5">
        <div class="section-header mb-3">
          <h6 class="fw-bold text-uppercase mb-1">
            <i class="bi bi-building me-2"></i>Select Venue & Studios
          </h6>
          <p class="text-muted small mb-0">Expand each venue to select studios</p>
        </div>

        <!-- Venue Accordions -->
        <div class="venue-accordion-list">
          <div
            v-for="(venue, index) in movieVenues"
            :key="venue.id"
            class="venue-accordion"
            :class="{ expanded: expandedVenueIds.includes(venue.id), 'has-selection': getSelectedStudiosForVenue(venue.id).length > 0 }"
          >
            <!-- Venue Header -->
            <div class="venue-accordion-header" @click="toggleVenueExpand(venue.id)">
              <div class="venue-header-left">
                <span class="venue-number">{{ index + 1 }}.</span>
                <i class="bi bi-building venue-icon"></i>
                <div class="venue-header-info">
                  <span class="venue-header-name">{{ venue.name }}</span>
                  <span class="venue-header-meta">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ venue.city }}
                  </span>
                </div>
              </div>
              <div class="venue-header-right">
                <span v-if="getSelectedStudiosForVenue(venue.id).length > 0" class="selected-badge">
                  {{ getSelectedStudiosForVenue(venue.id).length }} studio{{ getSelectedStudiosForVenue(venue.id).length > 1 ? 's' : '' }}
                </span>
                <i class="bi chevron-icon" :class="expandedVenueIds.includes(venue.id) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
              </div>
            </div>

            <!-- Studios Panel -->
            <Transition name="accordion">
              <div v-if="expandedVenueIds.includes(venue.id)" class="venue-accordion-body">
                <div class="studio-grid">
                  <button
                    v-for="studio in venue.studios"
                    :key="studio.id"
                    type="button"
                    class="studio-pill"
                    :class="{ selected: selectedStudioIds.includes(studio.id) }"
                    @click="toggleStudio(studio.id)"
                  >
                    <i class="bi" :class="selectedStudioIds.includes(studio.id) ? 'bi-check-lg' : 'bi-display'"></i>
                    {{ studio.name }}
                  </button>
                </div>

                <div v-if="!venue.studios.length" class="empty-state-small">
                  <i class="bi bi-exclamation-circle"></i>
                  <span>No studios available</span>
                </div>

                <div v-if="venue.studios.length" class="select-all-row">
                  <button type="button" class="btn-select-all" @click="toggleSelectAllStudios(venue)">
                    <i class="bi" :class="isAllStudiosSelected(venue) ? 'bi-check-square-fill' : 'bi-square'"></i>
                    {{ isAllStudiosSelected(venue) ? 'Deselect All' : 'Select All' }}
                  </button>
                </div>
              </div>
            </Transition>
          </div>

          <div v-if="!movieVenues.length" class="empty-state">
            <i class="bi bi-inbox fs-3"></i>
            <span>No cinema venues found</span>
          </div>
        </div>

        <!-- Summary -->
        <div v-if="selectedStudioIds.length > 0" class="selection-summary mt-4">
          <i class="bi bi-check-circle-fill text-success me-2"></i>
          <span>{{ selectedStudioIds.length }} studio{{ selectedStudioIds.length > 1 ? 's' : '' }} selected across {{ selectedVenueCount }} venue{{ selectedVenueCount > 1 ? 's' : '' }}</span>
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
const selectedStudioIds = ref([])
const expandedVenueIds = ref([])

const posterFile = ref(null)
const posterPreview = ref(null)

const toggleVenueExpand = (venueId) => {
  if (expandedVenueIds.value.includes(venueId)) {
    expandedVenueIds.value = expandedVenueIds.value.filter(id => id !== venueId)
  } else {
    expandedVenueIds.value.push(venueId)
  }
}

const getSelectedStudiosForVenue = (venueId) => {
  const venue = movieVenues.value.find(v => v.id === venueId)
  if (!venue) return []
  const venueStudioIds = venue.studios.map(s => s.id)
  return selectedStudioIds.value.filter(id => venueStudioIds.includes(id))
}

const selectedVenueCount = computed(() => {
  return movieVenues.value.filter(v => getSelectedStudiosForVenue(v.id).length > 0).length
})

const toggleStudio = (studioId) => {
  if (selectedStudioIds.value.includes(studioId)) {
    selectedStudioIds.value = selectedStudioIds.value.filter(id => id !== studioId)
  } else {
    selectedStudioIds.value.push(studioId)
  }
}

const isAllStudiosSelected = (venue) => {
  const studioIds = venue.studios.map(s => s.id)
  return studioIds.length > 0 && studioIds.every(id => selectedStudioIds.value.includes(id))
}

const toggleSelectAllStudios = (venue) => {
  const studioIds = venue.studios.map(s => s.id)
  if (isAllStudiosSelected(venue)) {
    selectedStudioIds.value = selectedStudioIds.value.filter(id => !studioIds.includes(id))
  } else {
    selectedStudioIds.value = [...new Set([...selectedStudioIds.value, ...studioIds])]
  }
}

onMounted(async () => {
  const res = await axios.get('/api/admin/venues-with-studios')
  venues.value = res.data
})

const movieVenues = computed(() => {
  return venues.value.filter(v => v.venue_type === 'Cinema')
})

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

  if (selectedStudioIds.value.length === 0) {
    alert('Please select at least one studio')
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
/* Poster Preview */
.poster-preview-box {
  width: 220px;
  height: 320px;
  border-radius: 16px;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border: 2px dashed #e2e8f0;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.poster-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #94a3b8;
  font-size: 13px;
  font-weight: 500;
}

.venue-studio-section {
  position: relative;
  background: transparent;
  width: 100%;
}

.venue-studio-section::before {
  content: '';
  width: 100%;
  position: absolute;
  inset: 0;
  -webkit-mask: 
    linear-gradient(#fff 0 0) content-box, 
    linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
}

.section-header h6 {
  color: #1e293b;
  font-size: 0.95rem;
  letter-spacing: 0.5px;
}

.venue-accordion-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.venue-accordion {
  background: #fff;
  border-radius: 14px;
  border: 2px solid #e2e8f0;
  overflow: hidden;
  transition: all 0.2s ease;
}

.venue-accordion:hover {
  border-color: #c7d2fe;
}

.venue-accordion.expanded {
  border-color: #6366f1;
  box-shadow: 0 4px 16px rgba(99, 102, 241, 0.1);
}

.venue-accordion.has-selection {
  border-color: #a5b4fc;
  background: linear-gradient(135deg, #fefefe, #f8faff);
}

.venue-accordion.has-selection.expanded {
  border-color: #6366f1;
}

.venue-accordion-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 18px;
  cursor: pointer;
  transition: background 0.15s ease;
}

.venue-accordion-header:hover {
  background: #f8fafc;
}

.venue-header-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.venue-number {
  font-weight: 700;
  font-size: 14px;
  color: #6366f1;
  min-width: 20px;
}

.venue-icon {
  font-size: 18px;
  color: #6366f1;
}

.venue-header-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.venue-header-name {
  font-weight: 600;
  font-size: 14px;
  color: #1e293b;
}

.venue-header-meta {
  font-size: 11px;
  color: #64748b;
}

.venue-header-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.selected-badge {
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 20px;
}

.chevron-icon {
  font-size: 12px;
  color: #94a3b8;
  transition: transform 0.2s ease;
}

.venue-accordion-body {
  padding: 0 18px 18px 18px;
  border-top: 1px solid #f1f5f9;
}

.studio-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding-top: 14px;
}

.studio-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border-radius: 10px;
  padding: 8px 14px;
  font-weight: 600;
  font-size: 13px;
  background: #f1f5f9;
  border: 2px solid #e2e8f0;
  color: #64748b;
  cursor: pointer;
  transition: all 0.2s ease;
}

.studio-pill:hover {
  background: #e0e7ff;
  border-color: #a5b4fc;
  color: #4f46e5;
}

.studio-pill.selected {
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  border-color: transparent;
  color: white;
  box-shadow: 0 3px 10px rgba(99, 102, 241, 0.3);
}

.select-all-row {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px dashed #e2e8f0;
}

.btn-select-all {
  background: none;
  border: none;
  color: #6366f1;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 0;
}

.btn-select-all:hover {
  color: #4f46e5;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 30px 20px;
  color: #94a3b8;
  text-align: center;
}

.empty-state span {
  font-size: 13px;
}

.empty-state-small {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 16px;
  color: #94a3b8;
  font-size: 12px;
}

/* Selection Summary */
.selection-summary {
  display: flex;
  align-items: center;
  background: #ecfdf5;
  border: 1px solid #a7f3d0;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 13px;
  font-weight: 500;
  color: #065f46;
}

/* Accordion Transition */
.accordion-enter-active {
  transition: all 0.25s ease-out;
}

.accordion-leave-active {
  transition: all 0.2s ease-in;
}

.accordion-enter-from,
.accordion-leave-to {
  opacity: 0;
  max-height: 0;
}

.accordion-enter-to,
.accordion-leave-from {
  opacity: 1;
  max-height: 300px;
}
</style>
