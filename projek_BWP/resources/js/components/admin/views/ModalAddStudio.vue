<template>
  <Teleport to="body">
    <div class="modal-overlay" @click.self="close">
      <div class="modal-box">

        <!-- HEADER -->
        <div class="modal-header">
          <div>
            <h5 class="fw-bold mb-0">Add Studio</h5>
            <small class="text-muted">
              Register a new studio for this venue
            </small>
          </div>
          <button class="close-btn" @click="close">×</button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <!-- STUDIO NAME -->
          <div class="form-group">
            <label>Studio Name</label>
            <input
              type="text"
              v-model="form.studio_name"
              class="form-control"
              placeholder="e.g. Studio 3"
            />
          </div>

          <!-- TYPE (DEFAULT & DISABLED) -->
          <div class="form-group">
            <label>Type</label>
            <input
              type="text"
              class="form-control"
              value="Regular"
              disabled
            />
          </div>

          <!-- CAPACITY -->
          <div class="form-group">
            <label>Capacity (Seats)</label>
            <input
              type="number"
              v-model="form.capacity"
              class="form-control"
              placeholder="e.g. 32" step="8" max="56" min="8"/>
          </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button
            class="btn btn-light rounded-pill"
            @click="close"
          >
            Cancel
          </button>

          <button
            class="btn btn-primary rounded-pill"
            :disabled="!canSubmit"
            @click="save"
          >
            Save Studio
          </button>
        </div>

      </div>
    </div>
  </Teleport>
</template>



<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  venueId: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['close', 'saved'])

const form = ref({
  studio_name: '',
  studio_type: 'Regular',
  capacity: ''
})

const close = () => {
  emit('close')
}

const canSubmit = computed(() => {
  return form.value.studio_name && form.value.capacity
})

const save = async () => {
  try {
    await axios.post(`/api/venues/${props.venueId}/studios`, {
      studio_name: form.value.studio_name
    })

    close()
  } catch (err) {
    console.error('Failed to save studio', err)
  }
}

onMounted(() => {
  document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
  document.body.style.overflow = ''
})
</script>


<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.modal-box {
  background: white;
  border-radius: 18px;
  width: 100%;
  max-width: 480px;
  padding: 24px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 26px;
  line-height: 1;
  cursor: pointer;
}

.modal-body {
  display: grid;
  gap: 16px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 6px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
}
</style>
