<template>
  <div class="container my-5 animate-fadeIn" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
      <button
        class="btn btn-light rounded-circle p-3"
        @click="$emit('cancel')"
      >
        ←
      </button>

      <div>
        <h2 class="fw-bold mb-0">Create New Entry</h2>
        <small class="text-muted fw-semibold">
          Add a new movie to the active catalog
        </small>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="card shadow-lg border-0 p-4">
      <div class="row g-4">
        <!-- LEFT FORM -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">
              Movie Title
            </label>
            <input
              v-model="movie.title"
              type="text"
              class="form-control form-control-lg"
              placeholder="e.g. Sonic the Hedgehog 3"
              required
            />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Genre</label>
            <input v-model="movie.genre" type="text" class="form-control form-control-lg" placeholder="e.g. Action, Adventure" required/>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">Description</label>
            <input v-model="movie.description" type="text" class="form-control form-control-lg" placeholder="Description of the Movie" required/>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Age Rating</label>
              <select v-model="movie.ageRating" class="form-select form-select-lg" >
                <option>SU</option>
                <option>13+</option>
                <option>17+</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Price</label>
              <input v-model="movie.price" type="number" class="form-control form-control-lg" placeholder="45000" required step="5000"/>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-semibold text-uppercase small">Movie Duration</label>
              <input v-model="movie.durationMinutes" type="number" class="form-control form-control-lg" placeholder="120 minutes" required step="15"/>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Release Date</label>
              <input v-model="movie.releaseDate" type="date" class="form-control form-control-lg" placeholder="Dec 20, 2024" required/>
            </div>

            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">Main di Bioskop Brp Hari</label>
              <input v-model="movie.playingTime" type="number" class="form-control form-control-lg" placeholder="30 days" required/>
            </div>
          </div> 

          <div class="mb-3">
            <label class="form-label fw-semibold text-uppercase small">
              Poster Image
            </label>
            <!-- <input
              v-model="movie.posterUrl"
              type="url"
              class="form-control form-control-lg"
              placeholder="https://images..."
              required
            /> -->
            <input class="form-control" type="file" id="fileInput" name="file" accept="image/*" @change="handleFileChange">
          </div>
        </div>

        <!-- RIGHT PREVIEW -->
        <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
          <div
            class="border rounded-4 p-3 bg-light"
            style="width: 200px; height: 300px;"
          >
            <img
              v-if="movie.posterUrl"
              :src="movie.posterUrl"
              class="img-fluid h-100 w-100 object-fit-cover rounded-3"
            />
            <div
              v-else
              class="h-100 d-flex align-items-center justify-content-center text-muted small fw-semibold"
            >
              Poster Preview
            </div>
          </div>

          <small class="text-muted mt-3 text-center">
            The poster updates automatically when URL changes
          </small>
        </div>
      </div>

      <!-- ACTIONS -->
      <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
        <button
          type="button"
          class="btn btn-link text-secondary fw-bold"
          @click="$emit('cancel')"
        >
          DISCARD
        </button>

        <button
          type="submit"
          class="btn btn-primary px-5 fw-bold"
        >
          PUBLISH MOVIE
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['cancel'])

const movie = reactive({
  title: '',
  genre: '',
  description: '',
  releaseDate: '',
  playingTime: 7,          // days
  ageRating: '13+',
  price: 45000,
  durationMinutes: 120     // movie duration
})

const posterFile = ref(null)
const posterPreview = ref(null)

/* capture file + preview */
const handleFileChange = (e) => {
  const file = e.target.files[0]
  if (!file) return

  posterFile.value = file
  posterPreview.value = URL.createObjectURL(file)
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

  try {
  await axios.post('/api/admin/movies', formData)

  alert('Movie created successfully')
  emit('cancel')

} catch (error) {
  const res = error.response

  if (res?.status === 409 && res.data?.type === 'studio_full') {
    alert(
      '⚠️ All studios are currently full.\n' +
      'Please create a new studio before adding this movie.'
    )
  } else {
    alert('Failed to create movie')
  }
}

}
</script>


