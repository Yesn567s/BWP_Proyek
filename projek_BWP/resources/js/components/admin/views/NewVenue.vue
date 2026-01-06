<style scoped>
.register-wrapper {
  min-height: 100vh;
  background: #f6f8fc;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px;
}

.venue-card {
  width: 100%;
  max-width: 820px;
  background: #ffffff;
  border-radius: 20px;
  padding: 32px 36px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

.soft-input {
  background: #f9fafb;
  border: none;
  border-radius: 12px;
  padding: 12px 14px;
}

.soft-input:focus {
  background: #ffffff;
  box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.15);
}

.form-label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #9aa4b2;
}

.btn-back {
  border-radius: 10px;
  padding: 6px 10px;
}
</style>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const form = ref({
  venue_name: '',
  location: '',
})

const loading = ref(false)
const errors = ref({})

const submitVenue = async () => {
  loading.value = true
  errors.value = {}

  try {
    await axios.post('/api/admin/venues', form.value)

    router.push({ name: 'adminCinemaCatalog' })
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors
    } else {
      console.error(err)
      alert('Failed to register venue')
    }
  } finally {
    loading.value = false
  }
}

const cancel = () => {
  router.back()
}

const goBack = () => {
  router.push({ name: 'adminMovieCatalog' })
}
</script>


<template>
  <div class="register-wrapper">
    <div class="venue-card">
      <!-- Header -->
      <div class="d-flex align-items-center mb-4">
        <button class="btn btn-light btn-back me-3" @click="goBack">
          ←
        </button>
        <div>
          <h4 class="mb-0 fw-bold">Register New Venue</h4>
          <small class="text-muted">
            Add a new cinema partner to your network
          </small>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitVenue">
        <div class="mb-3">
            <label class="form-label">Venue Name</label>
            <input type="text" class="form-control soft-input" v-model="form.venue_name" placeholder="e.g. XXI Pakuwon City"/>
            <small v-if="errors.venue_name" class="text-danger">
            {{ errors.venue_name[0] }}
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control soft-input" v-model="form.location" placeholder="Surabaya"/>
            <small v-if="errors.location" class="text-danger">
            {{ errors.location[0] }}
            </small>
        </div>

        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-link text-muted" @click="cancel">
            Cancel
            </button>
            <button type="submit" class="btn btn-primary px-4" :disabled="loading">
            {{ loading ? 'Saving...' : 'Register Venue' }}
            </button>
        </div>
        </form>

    </div>
  </div>
</template>
