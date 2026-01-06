<template>
  <div class="container my-5" style="max-width: 720px;">
    <div class="d-flex align-items-center gap-3 mb-4">
      <button class="btn btn-light rounded-circle p-3" @click="goBack">
        ←
      </button>
      <div>
        <h3 class="fw-bold mb-0">Edit Food & Beverage</h3>
        <small class="text-muted">Available across all cinema venues</small>
      </div>
    </div>

    <form class="card shadow-sm border-0 p-4" @submit.prevent="handleSubmit">
      <div class="mb-3">
        <label class="form-label fw-semibold text-uppercase small">Title</label>
        <input v-model="form.title" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold text-uppercase small">Price (IDR)</label>
        <input v-model.number="form.price" type="number" class="form-control" required min="1000" step="500" />
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold text-uppercase small">Description</label>
        <textarea v-model="form.description" class="form-control" rows="3" placeholder="Optional"></textarea>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold text-uppercase small">Image</label>
        <input ref="fileInput" type="file" class="form-control" accept="image/*" @change="onFile" />
        <div class="preview-wrapper mt-3">
          <div class="preview-box">
            <img v-if="posterPreview" :src="posterPreview" class="img-fluid h-100 w-100 object-fit-cover" alt="Preview" />
            <div v-else class="preview-placeholder text-muted">
              <i class="bi bi-image"></i>
              <span>Preview</span>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-3">
        <button type="button" class="btn btn-outline-secondary" @click="goBack">Cancel</button>
        <button type="submit" class="btn btn-primary px-4" :disabled="submitting">
          <span v-if="submitting">Saving...</span>
          <span v-else>Save</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const submitting = ref(false)
const posterPreview = ref(null)
const form = reactive({
  title: '',
  price: 0,
  description: '',
  poster: null,
})

onMounted(async () => {
  try {
    const res = await axios.get(`/api/admin/food/${route.params.id}`)
    form.title = res.data.title
    form.price = res.data.price
    form.description = res.data.description || ''
    posterPreview.value = res.data.imageUrl || null
  } catch (err) {
    alert('Food item not found')
    goBack()
  }
})

const onFile = (e) => {
  const file = e.target.files?.[0]
  if (!file) return
  form.poster = file
  posterPreview.value = URL.createObjectURL(file)
}

const goBack = () => {
  router.push({ name: 'adminFoodCatalog' })
}

const handleSubmit = async () => {
  const fd = new FormData()
  fd.append('title', form.title)
  fd.append('price', Math.round(form.price || 0))
  fd.append('description', form.description)
  fd.append('_method', 'PUT')
  if (form.poster) {
    fd.append('poster', form.poster)
  }

  submitting.value = true
  try {
    await axios.post(`/api/admin/food/${route.params.id}`, fd)
    alert('Food item updated')
    goBack()
  } catch (err) {
    alert('Failed to update food item')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.preview-wrapper {
  display: flex;
  justify-content: flex-start;
}

.preview-box {
  width: 180px;
  height: 180px;
  border: 2px dashed #e2e8f0;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  overflow: hidden;
}

.preview-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  font-size: 13px;
}

.preview-placeholder i {
  font-size: 20px;
}
</style>
