<template>
  <section class="admin-section">
    <header class="section-header">
      <p class="eyebrow">Realtime overview</p>
      <h1>Dashboard</h1>
      <p class="text-muted mb-0">Live sales, products, and orders snapshot.</p>
    </header>

    <div class="row g-4 mb-5" v-if="loaded">
      <div v-for="(stat, i) in statCards" :key="i" class="col-sm-6 col-lg-3">
        <div class="panel-card stat-card h-100">
          <small class="text-uppercase fw-semibold">{{ stat.label }}</small>
          <h3 class="fw-bold mt-2 mb-1">{{ stat.value }}</h3>
          <small class="text-muted">{{ stat.subtext }}</small>
        </div>
      </div>
    </div>

    <div v-else class="text-muted">Loading dashboard...</div>

    <div class="row g-4 align-items-stretch" v-if="loaded">
      <div class="col-lg-8">
        <div class="panel-card chart-card h-100">
          <h5 class="fw-bold mb-3">Revenue (last 7 days)</h5>
          <div v-if="revenueTrend.length" class="d-flex flex-column gap-2">
            <div v-for="point in revenueTrend" :key="point.day" class="d-flex justify-content-between align-items-center">
              <span class="text-muted">{{ point.day }}</span>
              <strong>{{ formatCurrency(point.total) }}</strong>
            </div>
          </div>
          <div v-else class="text-muted">No revenue yet.</div>

          <hr class="my-4" />
          <h6 class="fw-bold mb-2">Top Categories</h6>
          <div v-if="categoryBreakdown.length" class="d-flex flex-column gap-2">
            <div v-for="cat in categoryBreakdown" :key="cat.category" class="d-flex justify-content-between">
              <span>{{ cat.category }}</span>
              <span class="text-muted">{{ cat.units }} sold • {{ formatCurrency(cat.revenue) }}</span>
            </div>
          </div>
          <div v-else class="text-muted">No category sales yet.</div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="panel-card leaderboard-card h-100">
          <h5 class="fw-bold mb-3">🏆 Top Products</h5>
          <div v-if="topProducts.length" class="d-flex flex-column gap-3">
            <div
              v-for="(product, i) in topProducts"
              :key="product.id || i"
              class="d-flex align-items-center gap-3 pb-3 border-bottom"
            >
              <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-weight: 700;">
                {{ i + 1 }}
              </div>
              <div class="flex-grow-1">
                <strong class="d-block">{{ product.name }}</strong>
                <small class="text-muted">{{ product.category }}</small>
              </div>
              <div class="text-end">
                <div class="fw-bold">{{ formatCurrency(product.revenue) }}</div>
                <small class="text-muted">{{ product.units }} sold</small>
              </div>
            </div>
          </div>
          <div v-else class="text-muted">No products sold yet.</div>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-1" v-if="loaded">
      <div class="col-12">
        <div class="panel-card h-100">
          <h5 class="fw-bold mb-3">Recent Orders</h5>
          <div v-if="recentOrders.length" class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Date</th>
                  <th class="text-end">Items</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recentOrders" :key="order.id">
                  <td>#{{ order.id }}</td>
                  <td>{{ order.order_date }}</td>
                  <td class="text-end">{{ order.items }}</td>
                  <td class="text-end">{{ formatCurrency(order.total_price) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-muted">No orders yet.</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'

const loaded = ref(false)
const stats = ref(null)
const revenueTrend = ref([])
const categoryBreakdown = ref([])
const topProducts = ref([])
const recentOrders = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/admin/dashboard')
    stats.value = res.data.stats
    revenueTrend.value = res.data.revenueTrend || []
    categoryBreakdown.value = res.data.categoryBreakdown || []
    topProducts.value = res.data.topProducts || []
    recentOrders.value = res.data.recentOrders || []
  } catch (err) {
    console.error('Failed to load dashboard', err)
  } finally {
    loaded.value = true
  }
})

const statCards = computed(() => {
  if (!stats.value) return []
  return [
    { label: 'Total Revenue', value: formatCurrency(stats.value.totalRevenue), subtext: 'All time' },
    { label: 'Orders', value: formatNumber(stats.value.totalOrders), subtext: 'All time' },
    { label: 'Users', value: formatNumber(stats.value.totalUsers), subtext: 'Registered' },
    { label: 'Products', value: formatNumber(stats.value.totalProducts), subtext: 'Active SKUs' },
  ]
})

const formatCurrency = (value) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(Number(value) || 0)

const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(Number(value) || 0)
</script>
