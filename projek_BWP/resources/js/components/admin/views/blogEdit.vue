<template>
  <div class="edit-page container py-4">

    <!-- HEADER -->
    <div class="d-flex align-items-center gap-3 mb-4">
      <button class="btn btn-light rounded-circle" @click="goBack">
        ←
      </button>
      <div>
        <h5 class="fw-bold mb-0">Edit Article</h5>
        <small class="text-muted">Crafting stories for your community</small>
      </div>
    </div>

    <!-- CARD -->
    <div class="edit-card mx-auto">
      <div class="card-body">

        <!-- POST TITLE -->
        <div class="mb-4">
          <label class="form-label small text-muted">POST TITLE</label>
          <input
            v-model="form.title"
            class="form-control soft-input"
            placeholder="Post title"
          />
        </div>

        <!-- CATEGORY + STATUS -->
        <!-- <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label small text-muted">CATEGORY</label>
            <input
              v-model="form.category"
              class="form-control soft-input"
              placeholder="Category"
            />
          </div>

          <div class="col-md-6">
            <label class="form-label small text-muted">STATUS</label>
            <select v-model="form.status" class="form-select soft-input">
              <option>Draft</option>
              <option>Publish Live</option>
            </select>
          </div>
        </div> -->

        <!-- SUMMARY -->
        <!-- <div class="mb-4">
          <label class="form-label small text-muted">SUMMARY / EXCERPT</label>
          <textarea
            v-model="form.summary"
            rows="3"
            class="form-control soft-input"
          ></textarea>
        </div> -->

        <!-- BODY -->
        <div class="mb-4">
          <label class="form-label small text-muted">BODY CONTENT</label>
          <textarea
            v-model="form.content"
            rows="6"
            class="form-control soft-input"
          ></textarea>
        </div>

        <!-- COVER IMAGE -->
        <div class="mb-4">
          <label class="form-label small text-muted">COVER IMAGE URL</label>
          <input
            v-model="form.cover"
            class="form-control soft-input"
            placeholder="https://..."
          />
        </div>

        <!-- ACTIONS -->
        <div class="d-flex justify-content-between align-items-center">
          <button class="btn btn-link text-muted" @click="goBack">
            Discard
          </button>

          <button class="btn btn-primary px-4" @click="updatePost">
            Update Post
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const form = reactive({
  title: '',
  content: '',
})

const goBack = () => router.back()

const updatePost = async () => {
  try {
    await axios.put(`/api/posts/${route.params.id}`, {
      title: form.title,
      content: form.content
    })

    alert('Post updated successfully')
    router.back()
  } catch (error) {
    console.error(error)

    if (error.response?.status === 422) {
      alert('Validation failed. Please check your inputs.')
    } else {
      alert('Failed to update post')
    }
  }
}

onMounted(async () => {
  try {
    const res = await axios.get(`/api/posts/${route.params.id}`)

    form.title = res.data.title
    form.content = res.data.content
  } catch (error) {
    console.error(error)
    alert('Failed to load post')
  }
})
</script>

<style scoped>
.edit-page {
  max-width: 900px;
}

.edit-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  padding: 24px;
}

.soft-input {
  background: #f7f9fc;
  border: none;
  border-radius: 12px;
  padding: 12px 14px;
}

.soft-input:focus {
  background: #fff;
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.15);
}
</style>
