<style scoped>
.editor-page {
    background: #f6f8fc;
    min-height: 100vh;
}

.editor-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.editor-body {
    padding: 32px;
}

.editor-label {
    font-size: 0.7rem;
    letter-spacing: 0.08em;
    font-weight: 600;
    color: #9aa3b2;
    margin-bottom: 6px;
    display: block;
}

.editor-input {
    width: 100%;
    padding: 14px 18px;
    border-radius: 999px;
    border: none;
    background: #f3f5fa;
    font-size: 0.95rem;
}

.editor-input:focus {
    outline: none;
    background: #eef1f9;
}

.editor-textarea {
    width: 100%;
    padding: 16px 18px;
    border-radius: 18px;
    border: none;
    background: #f3f5fa;
    min-height: 160px;
    resize: none;
}

.editor-textarea.small {
    min-height: 90px;
}

.editor-textarea:focus {
    outline: none;
    background: #eef1f9;
}

.editor-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

/* PREVIEW */
.preview-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    height: 100%;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-img {
    width: 100%;
    height: auto;
    border-radius: 18px;
    object-fit: cover;
}

.preview-placeholder {
    color: #b0b7c3;
    font-size: 2rem;
    text-align: center;
}

.preview-placeholder small {
    display: block;
    font-size: 0.8rem;
}
</style>


<template>
  <div class="container py-4">
    <div class="row g-4">
      <!-- LEFT: FORM -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">

            <!-- ARTICLE TITLE -->
            <div class="mb-3">
              <label class="form-label text-muted small">ARTICLE TITLE</label>
              <input
                v-model="form.title"
                type="text"
                class="form-control form-control-lg rounded-pill"
                placeholder="The next big story..."
              />
            </div>

            <!-- BODY -->
            <div class="mb-3">
              <label class="form-label text-muted small">MAIN BODY CONTENT</label>
              <textarea
                v-model="form.content"
                class="form-control rounded-4"
                rows="6"
                placeholder="Tell your story..."
              ></textarea>
            </div>

            <!-- IMAGE URL -->
            <div class="mb-3">
              <label class="form-label text-muted small">FEATURE IMAGE URL</label>
              <input
                v-model="form.image"
                type="text"
                class="form-control rounded-pill"
                placeholder="https://images.unsplash.com/..."
              />
            </div>

            <!-- ACTIONS -->
            <div class="d-flex justify-content-end gap-2 mt-4">
              <button class="btn btn-light rounded-pill px-4">
                Discard
              </button>
              <button class="btn btn-primary rounded-pill px-4" @click="savePost">
                Save Post
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- RIGHT: IMAGE PREVIEW -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-3 d-flex align-items-center justify-content-center">
            <div v-if="form.image">
              <img
                :src="form.image"
                class="img-fluid rounded-4"
                alt="Preview"
              />
            </div>
            <div v-else class="text-muted text-center">
              <div class="fs-1">🖼️</div>
              <small>Image preview will appear here</small>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import axios from 'axios'

const form = reactive({
  title: '',
  content: '',
  image: ''
})

// Function to save post
const savePost = async () => {
  try {
    // POST request to Laravel API
    const response = await axios.post('/api/posts', {
      title: form.title,
      content: form.content,
      image: form.image, // remove if you don't save image in DB
    })

    alert('Post saved successfully!')

    // Clear the form
    form.title = ''
    form.content = ''
    form.image = ''
  } catch (error) {
    console.error(error.response || error)
    alert('Failed to save post. Check console for details.')
  }
}
</script>

