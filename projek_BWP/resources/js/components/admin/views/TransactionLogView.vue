<template>
  <section class="admin-section">
    <header class="section-header d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <p class="eyebrow">Operations</p>
        <h1 class="mb-1">Transaction Log</h1>
        <p class="text-muted mb-0">Recent orders with totals and item counts.</p>
      </div>
      <div class="d-flex gap-2">
        <input
          v-model="search"
          type="search"
          class="form-control"
          placeholder="Search order ID, name, email"
          @keyup.enter="fetchLogs(1)"
          style="min-width: 260px;"
        />
        <button class="btn btn-primary" :disabled="loading" @click="fetchLogs(1)">
          {{ loading ? 'Searching...' : 'Search' }}
        </button>
      </div>
    </header>

    <div class="row g-3 mb-4">
      <div v-for="card in statCards" :key="card.label" class=" col-sm-6 col-lg-3">
        <div class="stats-card panel-card stat-card h-100">
          <small class="text-uppercase fw-semibold text-muted">{{ card.label }}</small>
          <h4 class="fw-bold mt-2 mb-1">{{ card.value }}</h4>
          <small class="text-muted">{{ card.subtext }}</small>
        </div>
      </div>
    </div>

    <div class="panel-card p-0">
      <div class="table-responsive mb-0">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Order</th>
              <th>Customer</th>
              <th>Date</th>
              <th class="text-end">Items</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center text-muted py-4">Loading transactions...</td>
            </tr>
            <tr v-else-if="!rows.length">
              <td colspan="5" class="text-center text-muted py-4">No transactions found.</td>
            </tr>
            <tr v-for="row in rows" :key="row.id">
              <td>#{{ row.id }}</td>
              <td>
                <div class="fw-semibold">{{ row.user_name || 'Guest' }}</div>
                <small class="text-muted">{{ row.user_email || '—' }}</small>
              </td>
              <td>{{ row.order_date }}</td>
              <td class="text-end">{{ row.items }}</td>
              <td class="text-end">{{ formatCurrency(row.total_price) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
        <div class="text-muted small">
          Page {{ meta.current_page }} of {{ meta.last_page }} • {{ meta.total }} orders
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-light btn-sm" :disabled="loading || meta.current_page <= 1" @click="fetchLogs(meta.current_page - 1)">Previous</button>
          <button class="btn btn-light btn-sm" :disabled="loading || meta.current_page >= meta.last_page" @click="fetchLogs(meta.current_page + 1)">Next</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const rows = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0 })
const loading = ref(false)
const search = ref('')
const stats = ref(null)
const statCards = ref([])

const fetchLogs = async (page = 1) => {
  loading.value = true
  try {
    const res = await axios.get('/api/admin/transactions', {
      params: { page, q: search.value }
    })
    rows.value = res.data.data || []
    meta.value = res.data.meta || meta.value
    stats.value = res.data.stats || null
    statCards.value = buildStatCards(stats.value)
  } catch (err) {
    console.error('Failed to load transactions', err)
    rows.value = []
    meta.value = { current_page: 1, last_page: 1, total: 0 }
    stats.value = null
    statCards.value = []
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchLogs(1))

const formatCurrency = (value) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(Number(value) || 0)

const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(Number(value) || 0)

const buildStatCards = (s) => {
  if (!s) return []
  return [
    { label: 'Revenue', value: formatCurrency(s.totalRevenue), subtext: 'All time' },
    { label: 'Orders', value: formatNumber(s.totalOrders), subtext: 'All time' },
    { label: 'Customers', value: formatNumber(s.totalCustomers), subtext: 'Distinct buyers' },
    { label: 'Avg Order', value: formatCurrency(s.avgOrder), subtext: 'Avg order value' },
  ]
}
</script>

<style scoped>
.stats-card {
  background: linear-gradient(135deg, #fff459 0%, #daa544 30%, #fff459 100%);
}
</style>
