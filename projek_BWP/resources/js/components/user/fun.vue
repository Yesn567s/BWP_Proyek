<style scoped>
.cat-icon {
  width: 22px;
  height: 22px;
  object-fit: contain;
  margin-right: 6px;
}

.category-scroll {
  scrollbar-width: none;
}
.category-scroll::-webkit-scrollbar {
  display: none;
}

.ticket-card {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.ticket-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.card-img-top {
  transition: transform 0.5s ease;
}
.ticket-card:hover .card-img-top {
  transform: scale(1.05);
}

.search-input {
  padding-left: 3rem;
}
</style>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const search = ref('')
const categories = ref([])
const tickets = ref([])

/* FETCH ALL CATEGORIES */
onMounted(async () => {
  const res = await axios.get('/api/categories')
  categories.value = res.data
})

/* FETCH ALL TICKETS */
onMounted(async () => {
  const res = await axios.get('/api/tickets')
  tickets.value = res.data
})

/* 🟢 FUN CATEGORIES ONLY */
const funCategories = computed(() => {
  return categories.value.filter(cat => {
    const name = cat.category_name.toLowerCase()
    return !name.includes('movie') &&
           !name.includes('food') &&
           !name.includes('beverage')
  })
})

/* 🟢 FUN TICKETS */
const funTickets = computed(() => {
  return tickets.value.filter(t => {
    const catName = t.category?.toLowerCase() || ''
    return !catName.includes('movie') &&
           !catName.includes('food') &&
           !catName.includes('beverage')
  })
})

/* 🟢 FILTERED ITEMS */
const filteredItems = computed(() => {
  let data = funTickets.value

  // filter by category (ONLY if clicked)
  if (route.query.category) {
    data = data.filter(
      t => t.category_id == route.query.category
    )
  }

  // filter by search
  if (search.value) {
    data = data.filter(t =>
      t.title.toLowerCase().includes(search.value.toLowerCase())
    )
  }

  return data
})
</script>



<template>
  <div class="container py-5">

    <!-- HEADER -->
    <div class="row align-items-center mb-4 g-3">
      <div class="col-md">
        <h1 class="fw-bold">Explore Fun Tickets</h1>
        <p class="text-muted mb-0">
          Theme parks, attractions, playgrounds & more
        </p>
      </div>

      <!-- SEARCH -->
      <div class="col-md-5 position-relative">
        <input
          v-model="search"
          type="text"
          class="form-control form-control-lg rounded-pill search-input"
          placeholder="Search fun activities..."
        />
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      </div>
    </div>

    <!-- CATEGORIES -->
    <div class="d-flex gap-2 overflow-auto mb-4 pb-2 category-scroll">

      <!-- ALL -->
      <button
        class="btn rounded-pill fw-bold"
        :class="!route.query.category ? 'btn-primary' : 'btn-outline-secondary'"
        @click="router.push({ name: 'fun' })"
      >
        All Fun
      </button>

      <!-- FUN CATEGORIES -->
      <button
        v-for="cat in funCategories"
        :key="cat.category_id"
        class="btn rounded-pill fw-bold text-nowrap"
        :class="route.query.category == cat.category_id
          ? 'btn-primary'
          : 'btn-outline-secondary'"
        @click="router.push({ name: 'fun', query: { category: cat.category_id } })"
      >
        <img :src="`/${cat.icons}`" class="cat-icon" />
        {{ cat.category_name }}
      </button>

    </div>

    <!-- TICKETS GRID -->
    <div class="row g-4">
      <div
        v-for="item in filteredItems"
        :key="item.id"
        class="col-sm-6 col-lg-4 col-xl-3"
      >
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden ticket-card">

          <!-- IMAGE -->
          <img
            :src="item.imageUrl"
            class="card-img-top"
            style="height:200px; object-fit:cover"
          />

          <!-- BODY -->
          <div class="card-body">
            <span class="text-primary small fw-bold text-uppercase">
              {{ item.category }}
            </span>

            <h5 class="fw-bold mt-1">{{ item.title }}</h5>

            <p class="text-muted small mb-3">
              <i class="bi bi-geo-alt me-1"></i>
              {{ item.location }}
            </p>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <div>
                <small class="text-muted fw-bold text-uppercase">Price from</small>
                <div class="fs-5 fw-bold text-primary">
                  Rp {{ item.price }}
                </div>
              </div>

              <router-link
                :to="{ path: '/fun-schedule', query: { productId: item.id } }"
              >
                <button class="btn btn-primary rounded-pill fw-bold px-3">
                  Book
                </button>
              </router-link>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>
