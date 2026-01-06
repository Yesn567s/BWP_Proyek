<!-- <template>
  <section class="admin-section">
    <header class="section-header d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div>
        <p class="eyebrow">Movie</p>
        <h1 class="mb-1">{{ movie?.title || 'Movie Details' }}</h1>
        <p class="text-muted mb-0">Quick peek at the selected title.</p>
      </div>
      <button class="admin-pill-btn small ghost" @click="goBack">← Back</button>
    </header>

    <div v-if="loading" class="text-muted">Loading...</div>
    <div v-else-if="!movie" class="text-danger">Movie not found.</div>

    <div v-else class="panel-card catalog-card">
      <div class="detail-grid">
        <div class="poster-stack">
          <img :src="posterPreview || movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Poster" />
          <div v-if="editing" class="mt-2">
            <label class="form-label small fw-semibold">Change Poster</label>
            <input type="file" accept="image/*" class="form-control" @change="onPosterChange" />
          </div>
        </div>

        <div class="details">
          <div class="d-flex gap-2 align-items-center mb-3">
            <span class="badge bg-primary">ID: {{ movie.id }}</span>
            <span class="badge bg-secondary">Price: {{ formatPrice(currentPrice) }}</span>
            <div class="ms-auto d-flex gap-2">
              <button v-if="!editing" type="button" class="btn btn-primary btn-sm" @click="startEditing">Edit</button>
              <div v-else class="d-flex gap-2">
                <button type="button" class="btn btn-light btn-sm" @click="cancelEditing">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" :disabled="saving" @click="handleSave">
                  {{ saving ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </div>
          </div>

          <div v-if="!editing">
            <h4 class="fw-bold mb-1">{{ movie.title }}</h4>
            <p class="text-muted mb-1">{{ movie.genre || 'Genre unavailable' }}</p>
            <p class="mb-3">{{ movie.desc || 'No description provided.' }}</p>

            <div class="info-rows">
              <div class="info-row" v-if="movie.duration">
                <strong>Duration</strong>
                <span>{{ movie.duration }} mins</span>
              </div>
              <div class="info-row" v-if="movie.age_rating || movie.ageRating">
                <strong>Age Rating</strong>
                <span>{{ movie.age_rating || movie.ageRating }}</span>
              </div>
            </div>
          </div>

          <div v-else>
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input v-model="editable.title" class="form-control" required />
            </div>

            <div class="mb-3">
              <label class="form-label">Genre</label>
              <input v-model="editable.genre" class="form-control" required />
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea v-model="editable.desc" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label class="form-label">Price (IDR)</label>
                <input v-model.number="editable.price" type="number" min="1000" step="0.01" inputmode="decimal" class="form-control" required />
              </div>
              <div class="col-md-4">
                <label class="form-label">Duration (minutes)</label>
                <input v-model.number="editable.duration" type="number" min="60" max="240" class="form-control" required />
              </div>
              <div class="col-md-4">
                <label class="form-label">Age Rating</label>
                <select v-model="editable.age_rating" class="form-select" required>
                  <option value="SU">SU</option>
                  <option value="13+">13+</option>
                  <option value="17+">17+</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template> -->

<template>
  <section class="admin-section">
    <header class="section-header d-flex justify-content-between align-items-center">
      <div>
        <p class="eyebrow">Movie</p>
        <h1 class="mb-1">{{ movie?.title || 'Movie Details' }}</h1>
        <p class="text-muted mb-0">Manage movie information.</p>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" @click="goBack">← Back</button>
        <button class="btn btn-danger" @click="deleteMovie">Delete</button>
      </div>
    </header>

    <div v-if="loading">Loading...</div>

    <div v-else class="panel-card">
      <form @submit.prevent="updateMovie">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input class="form-control" v-model="form.title" />
          </div>

          <div class="col-md-6">
            <label class="form-label">Genre</label>
            <input class="form-control" v-model="form.genre" />
          </div>

          <div class="col-md-6">
            <label class="form-label">Base Price</label>
            <input type="number" class="form-control" v-model="form.base_price" />
          </div>

          <div class="col-md-6">
            <label class="form-label">Age Rating</label>
            <input class="form-control" v-model="form.age_rating" />
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="4" v-model="form.description"></textarea>
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-3">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </section>
</template>


<script setup>
import { onMounted, ref, reactive, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const movie = ref(null)
const loading = ref(true)
const saving = ref(false)
const posterFile = ref(null)
const posterPreview = ref(null)
const editable = reactive({
  title: '',
  genre: '',
  desc: '',
  price: 0,
  duration: null,
  age_rating: 'SU',
})
const editing = ref(false)

const goBack = () => {
  router.push({ name: 'adminMovieCatalog' })
}

const formatPrice = (price) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(price || 0)

const currentPrice = computed(() => editing.value ? editable.price : movie.value?.price)

onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/movies')
    const list = res.data || []
    movie.value = list.find((m) => String(m.id) === String(route.params.id)) || null
    if (movie.value) {
      editable.title = movie.value.title
      editable.genre = movie.value.genre
      editable.desc = movie.value.desc
      editable.price = movie.value.price
      editable.duration = movie.value.duration
      editable.age_rating = movie.value.age_rating || movie.value.ageRating || 'SU'
    }
  } catch (err) {
    console.error('Failed to load movies', err)
    movie.value = null
  } finally {
    loading.value = false
  }
})

const startEditing = () => {
  if (!movie.value) return
  editing.value = true
  posterPreview.value = null
  posterFile.value = null
}

const cancelEditing = () => {
  if (!movie.value) return
  editing.value = false
  posterPreview.value = null
  posterFile.value = null
  editable.title = movie.value.title
  editable.genre = movie.value.genre
  editable.desc = movie.value.desc
  editable.price = movie.value.price
  editable.duration = movie.value.duration
  editable.age_rating = movie.value.age_rating || movie.value.ageRating || 'SU'
}

const onPosterChange = (e) => {
  const file = e.target.files?.[0]
  if (!file) return
  posterFile.value = file
  posterPreview.value = URL.createObjectURL(file)
}

const handleSave = async () => {
  if (!movie.value) return
  saving.value = true

  const formData = new FormData()
  formData.append('title', editable.title)
  formData.append('genre', editable.genre)
  formData.append('description', editable.desc)
  formData.append('price', editable.price)
  formData.append('duration_minutes', editable.duration)
  formData.append('ageRating', editable.age_rating)
  if (posterFile.value) {
    formData.append('poster', posterFile.value)
  }

  try {
    formData.append('_method', 'PUT')
    await axios.post(
      `/api/admin/movies/${movie.value.id}`,
      formData,
      { headers: { 'Content-Type': 'multipart/form-data' } }
    )
    alert('Movie updated')
    movie.value = {
      ...movie.value,
      title: editable.title,
      genre: editable.genre,
      desc: editable.desc,
      price: editable.price,
      duration: editable.duration,
      age_rating: editable.age_rating,
      poster: posterPreview.value || movie.value.poster,
    }
    editing.value = false
  } catch (err) {
    console.error('Failed to update movie', err)
    alert('Failed to update movie')
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.detail-grid {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 24px;
  align-items: start;
}

.poster-stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.movie-poster {
  width: 100%;
  max-width: 240px;
  height: 360px;
  object-fit: cover;
  border-radius: 12px;
}

.info-rows {
  display: grid;
  grid-template-rows: repeat(5, minmax(0, auto));
  gap: 10px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 12px;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 10px;
  background: #f8fafc;
}

@media (max-width: 768px) {
  .detail-grid {
    grid-template-columns: 1fr;
  }

  .movie-poster {
    max-width: 100%;
    height: 300px;
  }
}
</style>
