<template>
  <section class="admin-section">
    <header class="section-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
      <div>
        <p class="eyebrow">Concessions</p>
        <h1 class="mb-1">Food &amp; Beverages</h1>
        <p class="text-muted mb-0">Total items: <strong>{{ foods.length }}</strong></p>
      </div>
      <button class="btn btn-primary" @click="goAdd">Add Food</button>
    </header>

    <div class="row g-4">
      <div v-for="food in foods" :key="food.id" class="col-md-6 col-lg-4">
        <div class="panel-card catalog-card h-100">
          <div class="d-flex gap-3 align-items-center">
            <img :src="food.imageUrl || '/images/food/default.jpg'" class="movie-poster" alt="Food Image" />
            <div class="details">
              <h5 class="fw-bold mb-1">{{ food.title }}</h5>
              <p class="text-muted mb-2">{{ formatPrice(food.price) }}</p>
              <button class="admin-pill-btn small ghost btn-primary" @click="goEdit(food.id)">Edit</button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="!foods.length" class="col-12 text-center text-muted py-4">
        No food items yet.
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const foods = ref([])
const router = useRouter()
const goAdd = () => router.push({ name: 'adminAddFood' })
const goEdit = (id) => router.push({ name: 'adminEditFood', params: { id } })

onMounted(async () => {
  try {
    const res = await axios.get('/api/food')
    foods.value = res.data
  } catch (err) {
    console.error('Failed to load foods', err)
    foods.value = []
  }
})

const formatPrice = (price) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
}).format(price || 0)
</script>
