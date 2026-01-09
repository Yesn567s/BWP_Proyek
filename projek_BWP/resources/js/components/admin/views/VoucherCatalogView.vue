<template>
  <section class="admin-section">
    <header class="section-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
      <div>
        <p class="eyebrow">Promotions</p>
        <h1 class="mb-1">Voucher Management</h1>
        <p class="text-muted mb-0">Total vouchers: <strong>{{ vouchers.length }}</strong></p>
      </div>
      <button class="btn btn-primary" @click="openModal()">+ New Voucher</button>
    </header>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="panel-card stat-card text-center">
          <h3 class="fw-bold text-primary mb-0">{{ activeCount }}</h3>
          <small class="text-muted">Active</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="panel-card stat-card text-center">
          <h3 class="fw-bold text-warning mb-0">{{ expiredCount }}</h3>
          <small class="text-muted">Expired</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="panel-card stat-card text-center">
          <h3 class="fw-bold text-success mb-0">{{ totalUsage }}</h3>
          <small class="text-muted">Total Redemptions</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="panel-card stat-card text-center">
          <h3 class="fw-bold text-info mb-0">{{ percentVouchers }}%</h3>
          <small class="text-muted">Discount Vouchers</small>
        </div>
      </div>
    </div>

    <!-- Voucher List -->
    <div class="panel-card">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Code</th>
            <th>Title</th>
            <th>Discount</th>
            <th>Valid Period</th>
            <th>Usage</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="v in vouchers" :key="v.voucher_id">
            <td><code class="bg-light px-2 py-1 rounded">{{ v.code }}</code></td>
            <td>
              <strong>{{ v.title }}</strong>
              <br><small class="text-muted">{{ v.description || '-' }}</small>
            </td>
            <td>
              <span class="badge bg-primary">
                {{ v.discount_type === 'percent' ? v.discount_value + '%' : 'Rp ' + formatNumber(v.discount_value) }}
              </span>
            </td>
            <td>
              <small>{{ formatDate(v.start_date) }} - {{ formatDate(v.end_date) }}</small>
            </td>
            <td>
              {{ v.used_count }} / {{ v.max_usage ?? '∞' }}
            </td>
            <td>
              <span :class="['badge', getStatusBadge(v)]">{{ getStatusText(v) }}</span>
            </td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary me-1" @click="openModal(v)">Edit</button>
              <button class="btn btn-sm btn-outline-danger" @click="deleteVoucher(v.voucher_id)">Delete</button>
            </td>
          </tr>
          <tr v-if="!vouchers.length">
            <td colspan="7" class="text-center text-muted py-4">No vouchers found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal fade show d-block" style="background: rgba(0,0,0,.5)">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
          <div class="modal-header border-0">
            <h5 class="modal-title fw-bold">{{ isEdit ? 'Edit Voucher' : 'Create New Voucher' }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveVoucher">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Voucher Code *</label>
                  <input v-model="form.code" type="text" class="form-control" required placeholder="e.g. SUMMER20" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Title *</label>
                  <input v-model="form.title" type="text" class="form-control" required placeholder="e.g. Summer Sale" />
                </div>
                <div class="col-12">
                  <label class="form-label">Description</label>
                  <textarea v-model="form.description" class="form-control" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Discount Type *</label>
                  <select v-model="form.discount_type" class="form-select" required>
                    <option value="percent">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (Rp)</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Discount Value *</label>
                  <input v-model.number="form.discount_value" type="number" class="form-control" required min="1" />
                </div>
                <div class="col-md-4">
                  <label class="form-label">Max Usage</label>
                  <input v-model.number="form.max_usage" type="number" class="form-control" placeholder="Leave empty for unlimited" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Start Date *</label>
                  <input v-model="form.start_date" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">End Date *</label>
                  <input v-model="form.end_date" type="date" class="form-control" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Media Image</label>
                  <input type="file" class="form-control" accept="image/*" @change="onFileChange" />
                  <small class="text-muted">Upload an image or leave empty to keep the current one.</small>
                  <div v-if="mediaPreview || form.media" class="mt-2">
                    <img :src="mediaPreview || ('/' + form.media)" alt="Voucher" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                  </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                  <div class="form-check">
                    <input v-model="form.is_active" type="checkbox" class="form-check-input" id="isActive" />
                    <label class="form-check-label" for="isActive">Active</label>
                  </div>
                </div>
              </div>
              <div class="mt-4 d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                <button type="submit" class="btn btn-primary" :disabled="saving">
                  {{ saving ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const vouchers = ref([])
const showModal = ref(false)
const isEdit = ref(false)
const saving = ref(false)
const mediaFile = ref(null)
const mediaPreview = ref('')

const defaultForm = {
  voucher_id: null,
  code: '',
  title: '',
  description: '',
  discount_type: 'percent',
  discount_value: 10,
  start_date: '',
  end_date: '',
  max_usage: null,
  is_active: true,
  media: ''
}

const form = ref({ ...defaultForm })

onMounted(loadVouchers)

async function loadVouchers() {
  try {
    const res = await axios.get('/api/admin/vouchers')
    vouchers.value = res.data
  } catch (err) {
    console.error('Failed to load vouchers', err)
  }
}

const activeCount = computed(() => vouchers.value.filter(v => v.is_active && !isExpired(v)).length)
const expiredCount = computed(() => vouchers.value.filter(v => isExpired(v)).length)
const totalUsage = computed(() => vouchers.value.reduce((sum, v) => sum + (v.used_count || 0), 0))
const percentVouchers = computed(() => {
  const pct = vouchers.value.filter(v => v.discount_type === 'percent').length
  return vouchers.value.length ? Math.round((pct / vouchers.value.length) * 100) : 0
})

function isExpired(v) {
  return new Date(v.end_date) < new Date()
}

function getStatusBadge(v) {
  if (!v.is_active) return 'bg-secondary'
  if (isExpired(v)) return 'bg-warning text-dark'
  return 'bg-success'
}

function getStatusText(v) {
  if (!v.is_active) return 'Inactive'
  if (isExpired(v)) return 'Expired'
  return 'Active'
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatNumber(n) {
  return new Intl.NumberFormat('id-ID').format(n)
}

function openModal(voucher = null) {
  if (voucher) {
    isEdit.value = true
    form.value = { ...voucher }
    mediaFile.value = null
    mediaPreview.value = voucher.media ? `/${voucher.media}` : ''
  } else {
    isEdit.value = false
    const today = new Date().toISOString().split('T')[0]
    const nextMonth = new Date()
    nextMonth.setMonth(nextMonth.getMonth() + 1)
    form.value = { 
      ...defaultForm, 
      start_date: today,
      end_date: nextMonth.toISOString().split('T')[0]
    }
    mediaFile.value = null
    mediaPreview.value = ''
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  form.value = { ...defaultForm }
  mediaFile.value = null
  mediaPreview.value = ''
}

function onFileChange(e) {
  const file = e.target.files?.[0]
  if (!file) {
    mediaFile.value = null
    mediaPreview.value = ''
    return
  }
  mediaFile.value = file
  mediaPreview.value = URL.createObjectURL(file)
}

async function saveVoucher() {
  saving.value = true
  try {
    const payload = new FormData()
    payload.append('code', form.value.code)
    payload.append('title', form.value.title)
    payload.append('description', form.value.description || '')
    payload.append('discount_type', form.value.discount_type)
    payload.append('discount_value', form.value.discount_value)
    payload.append('start_date', form.value.start_date)
    payload.append('end_date', form.value.end_date)
    if (form.value.max_usage !== null && form.value.max_usage !== undefined && form.value.max_usage !== '') {
      payload.append('max_usage', form.value.max_usage)
    }
    payload.append('is_active', form.value.is_active ? 1 : 0)

    if (mediaFile.value) {
      payload.append('media', mediaFile.value)
    } else if (form.value.media) {
      payload.append('media_path', form.value.media)
    }

    if (isEdit.value) {
      await axios.put(`/api/admin/vouchers/${form.value.voucher_id}`, payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    } else {
      await axios.post('/api/admin/vouchers', payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    }

    closeModal()
    loadVouchers()
  } catch (err) {
    console.error('Save failed', err)
    alert(err.response?.data?.message || 'Failed to save voucher')
  } finally {
    saving.value = false
  }
}

async function deleteVoucher(id) {
  if (!confirm('Delete this voucher?')) return
  try {
    await axios.delete(`/api/admin/vouchers/${id}`)
    loadVouchers()
  } catch (err) {
    console.error('Delete failed', err)
    alert('Failed to delete voucher')
  }
}
</script>

<style scoped>
.stat-card {
  padding: 1.25rem;
}
.stat-card h3 {
  font-size: 1.75rem;
}
code {
  font-size: 0.85rem;
}
</style>
