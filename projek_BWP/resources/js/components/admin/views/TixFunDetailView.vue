<template>
  <section class="admin-section">
    <header class="section-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
      <div>
        <p class="eyebrow">TixFun</p>
        <h1 class="mb-1">{{ item?.name || 'Experience Details' }}</h1>
        <p class="text-muted mb-0">Manage a single experience ticket.</p>
      </div>
      <button class="admin-pill-btn small ghost" @click="goBack">← Back</button>
    </header>

    <div v-if="loading" class="text-muted">Loading...</div>
    <div v-else-if="!item" class="text-danger">Experience not found.</div>

    <div v-else class="panel-card catalog-card">
      <div class="detail-grid">
        <div class="poster-stack">
          <img :src="posterPreview || item.imageUrl || '/images/fun/default.jpg'" class="media-poster" alt="Cover" />
          <div v-if="editing" class="mt-2">
            <label class="form-label small fw-semibold">Change Image</label>
            <input type="file" accept="image/*" class="form-control" @change="onPosterChange" />
          </div>
        </div>

        <div class="details">
          <div class="d-flex gap-2 align-items-center mb-3">
            <span class="badge bg-primary">ID: {{ item.id }}</span>
            <span class="badge bg-secondary">Price: {{ formatPrice(currentPrice) }}</span>
            <span class="badge bg-info text-dark">{{ currentCategoryName || 'Category' }}</span>
            <div class="ms-auto d-flex gap-2">
              <button v-if="!editing" type="button" class="btn btn-primary btn-sm" @click="startEditing">Edit</button>
              <button v-if="!editing" type="button" class="btn btn-danger btn-sm" :disabled="deleting" @click="confirmDelete">
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
              <div v-else class="d-flex gap-2">
                <button type="button" class="btn btn-light btn-sm" @click="cancelEditing">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" :disabled="saving" @click="handleSave">
                  {{ saving ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </div>
          </div>

          <div v-if="!editing">
            <h4 class="fw-bold mb-1">{{ item.name }}</h4>
            <p class="text-muted mb-2">{{ item.genre || 'Genre unavailable' }}</p>
            <p class="mb-3">{{ item.description || 'No description provided.' }}</p>

            <div class="info-rows">
              <div class="info-row" v-if="item.duration_minutes">
                <strong>Duration</strong>
                <span>{{ item.duration_minutes }} mins</span>
              </div>
              <div class="info-row" v-if="item.age_rating">
                <strong>Age Rating</strong>
                <span>{{ item.age_rating }}</span>
              </div>
              <div class="info-row">
                <strong>Requires Schedule</strong>
                <span>{{ item.requires_schedule ? 'Yes' : 'No' }}</span>
              </div>
            </div>
          </div>

          <div v-else>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input v-model="editable.name" class="form-control" required />
              </div>
              <div class="col-md-6">
                <label class="form-label">Category</label>
                <select v-model="editable.category_id" class="form-select" required>
                  <option v-for="cat in funCategories" :key="cat.category_id" :value="cat.category_id">
                    {{ cat.category_name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea v-model="editable.description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label class="form-label">Price (IDR)</label>
                <input v-model.number="editable.base_price" type="number" min="0" step="500" class="form-control" required />
              </div>
              <div class="col-md-4">
                <label class="form-label">Duration (minutes)</label>
                <input v-model.number="editable.duration_minutes" type="number" min="0" class="form-control" />
              </div>
              <div class="col-md-4">
                <label class="form-label">Age Rating</label>
                <select v-model="editable.age_rating" class="form-select">
                  <option value="">All Ages</option>
                  <option value="SU">SU</option>
                  <option value="13+">13+</option>
                  <option value="17+">17+</option>
                </select>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Genre / Tags</label>
                <input v-model="editable.genre" class="form-control" />
              </div>
              <div class="col-md-6 d-flex align-items-center gap-2 mt-3 mt-md-0">
                <input v-model="editable.requires_schedule" type="checkbox" class="form-check-input" id="requiresSchedule" />
                <label class="form-check-label" for="requiresSchedule">Requires Schedule</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const item = ref(null)
const editing = ref(false)
const posterPreview = ref(null)
const posterFile = ref(null)
const categories = ref([])

const editable = reactive({
  name: '',
  category_id: '',
  description: '',
  base_price: 0,
  duration_minutes: null,
  age_rating: '',
  genre: '',
  requires_schedule: false,
})

const funCategories = computed(() => categories.value.filter(cat => {
  const name = (cat.category_name || '').toLowerCase()
  return !name.includes('movie') && !name.includes('food') && !name.includes('beverage')
}))

const currentCategoryName = computed(() => {
  const cat = categories.value.find(c => c.category_id === editable.category_id)
  return cat?.category_name || item.value?.category_name || ''
})

const currentPrice = computed(() => editing.value ? editable.base_price : item.value?.base_price)

const goBack = () => {
  router.push({ name: 'adminTixFunCatalog' })
}

const formatPrice = (price) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(Number(price) || 0)

const loadData = async () => {
  loading.value = true
  try {
    const [catRes, itemRes] = await Promise.all([
      axios.get('/api/categories'),
      axios.get(`/api/admin/tixfun/${route.params.id}`),
    ])

    categories.value = catRes.data || []
    item.value = itemRes.data

    editable.name = item.value.name
    editable.category_id = item.value.category_id
    editable.description = item.value.description
    editable.base_price = item.value.base_price
    editable.duration_minutes = item.value.duration_minutes
    editable.age_rating = item.value.age_rating || ''
    editable.genre = item.value.genre || ''
    editable.requires_schedule = !!item.value.requires_schedule
    posterPreview.value = item.value.imageUrl || null
  } catch (err) {
    console.error('Failed to load TixFun detail', err)
    alert('TixFun not found')
    goBack()
  } finally {
    loading.value = false
  }
}

onMounted(loadData)

const startEditing = () => {
  editing.value = true
  posterFile.value = null
  posterPreview.value = item.value?.imageUrl || null
}

const cancelEditing = () => {
  if (!item.value) return
  editing.value = false
  posterFile.value = null
  posterPreview.value = item.value.imageUrl || null
  editable.name = item.value.name
  editable.category_id = item.value.category_id
  editable.description = item.value.description
  editable.base_price = item.value.base_price
  editable.duration_minutes = item.value.duration_minutes
  editable.age_rating = item.value.age_rating || ''
  editable.genre = item.value.genre || ''
  editable.requires_schedule = !!item.value.requires_schedule
}

const onPosterChange = (e) => {
  const file = e.target.files?.[0]
  if (!file) return
  posterFile.value = file
  posterPreview.value = URL.createObjectURL(file)
}

const handleSave = async () => {
  if (!item.value) return
  saving.value = true

  const fd = new FormData()
  fd.append('name', editable.name)
  fd.append('category_id', editable.category_id)
  fd.append('description', editable.description)
  fd.append('base_price', Math.round(editable.base_price || 0))
  if (editable.duration_minutes) {
    fd.append('duration_minutes', editable.duration_minutes)
  }
  if (editable.age_rating) {
    fd.append('age_rating', editable.age_rating)
  }
  if (editable.genre) {
    fd.append('genre', editable.genre)
  }
  fd.append('requires_schedule', editable.requires_schedule ? 1 : 0)
  if (posterFile.value) {
    fd.append('image', posterFile.value)
  }
  fd.append('_method', 'PUT')

  try {
    await axios.post(`/api/admin/tixfun/${item.value.id}`, fd)
    item.value = {
      ...item.value,
      name: editable.name,
      category_id: editable.category_id,
      description: editable.description,
      base_price: editable.base_price,
      duration_minutes: editable.duration_minutes,
      age_rating: editable.age_rating,
      genre: editable.genre,
      requires_schedule: editable.requires_schedule,
      imageUrl: posterPreview.value || item.value.imageUrl,
    }
    editing.value = false
    alert('TixFun updated')
  } catch (err) {
    console.error('Failed to update TixFun', err)
    alert('Failed to update TixFun')
  } finally {
    saving.value = false
  }
}

const confirmDelete = () => {
  if (!item.value || deleting.value) return
  const confirmed = confirm(`Delete "${item.value.name}"? This cannot be undone.`)
  if (confirmed) {
    deleteItem()
  }
}

const deleteItem = async () => {
  if (!item.value) return
  deleting.value = true
  try {
    await axios.delete(`/api/admin/tixfun/${item.value.id}`)
    alert('TixFun deleted')
    goBack()
  } catch (err) {
    console.error('Failed to delete TixFun', err)
    alert('Failed to delete TixFun')
  } finally {
    deleting.value = false
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

.media-poster {
  width: 100%;
  max-width: 240px;
  height: 180px;
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

  .media-poster {
    max-width: 100%;
    height: 240px;
  }
}
</style>
