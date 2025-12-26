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
            <label class="form-label fw-semibold text-uppercase small">
              Genre
            </label>
            <input
              v-model="movie.genre"
              type="text"
              class="form-control form-control-lg"
              placeholder="e.g. Action, Adventure"
              required
            />
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">
                Rating
              </label>
              <select
                v-model="movie.rating"
                class="form-select form-select-lg"
              >
                <option>G</option>
                <option>PG</option>
                <option>PG-13</option>
                <option>R</option>
                <option>NC-17</option>
              </select>
            </div>

            <div class="col-6">
              <label class="form-label fw-semibold text-uppercase small">
                Release Date
              </label>
              <input
                v-model="movie.releaseDate"
                type="text"
                class="form-control form-control-lg"
                placeholder="Dec 20, 2024"
                required
              />
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
  rating: 'G',
  releaseDate: '',
})

const posterFile = ref(null)

/* capture file */
const handleFileChange = (e) => {
  posterFile.value = e.target.files[0]
}

const handleSubmit = async () => {
  if (!posterFile.value) {
    alert('Poster image is required')
    return
  }

  const formData = new FormData()
  formData.append('title', movie.title)
  formData.append('genre', movie.genre)
  formData.append('releaseDate', movie.releaseDate)
  formData.append('poster', posterFile.value)

  try {
    await axios.post('/api/admin/movies', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    alert('Movie created successfully')
    emit('cancel')

  } catch (error) {
    console.error(error)
    alert('Failed to create movie')
  }
}
</script>

