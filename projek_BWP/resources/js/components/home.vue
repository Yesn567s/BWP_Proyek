<style scoped>
.movie-card {
  height: 420px;
}

.movie-poster {
  width: 100%;
  height: 320px;      
  object-fit: cover;  
  display: block;
}

.img {
  height: 100px;
  object-fit: cover;
}

.poster-wrapper {
  height: 500px;
  overflow: hidden;
  border-top-left-radius: 0.375rem;
  border-top-right-radius: 0.375rem;
}

/* Image default (cropped) */
.movie-poster {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(1.2);
  transition: transform 0.4s ease;
}

/* Hover = reveal full poster */
.poster-wrapper:hover .movie-poster {
  transform: scale(1);
}

/* Container */
.deals-carousel {
  position: relative;
}

/* Horizontal scroll */
.deals-track {
  scroll-behavior: smooth;
  scrollbar-width: none;
}
.deals-track::-webkit-scrollbar {
  display: none;
}

/* Cards */
.deal-card {
  min-width: 320px;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: box-shadow 0.3s ease;
}

.deal-card:hover {
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

/* Image */
.deal-image-wrapper {
  position: relative;
  height: 160px;
  overflow: hidden;
}

.deal-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.deal-card:hover .deal-image {
  transform: scale(1.1);
}

/* Badge */
.deal-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: #0dcaf0;
  color: white;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: bold;
}

/* Arrows */
.carousel-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: none;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  z-index: 10;
  font-size: 22px;
  font-weight: bold;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.deals-carousel:hover .carousel-arrow {
  opacity: 1;
}

.carousel-arrow:hover {
  transform: translateY(-50%) scale(1.08);
}

.carousel-arrow.left {
  left: -22px;
}

.carousel-arrow.right {
  right: -22px;
}

.category-btn {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

/* Icon box */
.category-icon {
  width: 80px;
  height: 80px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Emoji size */
.icon-emoji {
  font-size: 36px;
}

/* Hover / active effects */
.category-btn:hover .category-icon {
  transform: scale(1.12);
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.category-btn:active .category-icon {
  transform: scale(0.95);
}

/* Category text */
.category-name {
  display: block;
  margin-top: 12px;
  font-weight: 700;
  font-size: 14px;
  color: #495057;
  transition: color 0.3s ease;
}

.category-btn:hover .category-name {
  color: #0d6efd; /* Bootstrap primary */
}

</style>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const movies = ref([])

onMounted(() => {
  axios.get('/api/movies').then(res => {
    console.log(res.data[0])
    movies.value = res.data
  })
})

const deals = ref([
  {
    id: 1,
    title: 'Family Pack',
    description: 'Buy 3 get 1 free for Waterpark',
    discount: 'B3G1',
    imageUrl: '/storage/posters/zootopia.jpg'
  },
  {
    id: 2,
    title: 'Arcade Mania',
    description: 'Double credits for every top-up',
    discount: '2X CREDITS',
    imageUrl: '/storage/posters/zootopia.jpg'
  },
  {
    id: 3,
    title: 'Night Show',
    description: 'Special price for late night movies',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia.jpg'
  },
  {
    id: 4,
    title: 'tess',
    description: 'alohaa',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia.jpg'
  },
  {
    id: 5,
    title: 'testistos',
    description: 'aaaaaaaa',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia.jpg'
  }
]);

const categories = ref([
  {
    id: 1,
    name: 'Movies',
    icon: '🎬',
    colorClass: 'bg-primary'
  },
  {
    id: 2,
    name: 'Concerts',
    icon: '🎵',
    colorClass: 'bg-success'
  },
  {
    id: 3,
    name: 'Sports',
    icon: '⚽',
    colorClass: 'bg-warning'
  },
  {
    id: 4,
    name: 'Theatre',
    icon: '🎭',
    colorClass: 'bg-danger'
  },
  {
    id: 5,
    name: 'Events',
    icon: '🎉',
    colorClass: 'bg-info'
  },
  {
    id: 6,
    name: 'Movies',
    icon: '🎬',
    colorClass: 'bg-primary'
  },
  {
    id: 7,
    name: 'Concerts',
    icon: '🎵',
    colorClass: 'bg-success'
  },
  {
    id: 8,
    name: 'Sports',
    icon: '⚽',
    colorClass: 'bg-warning'
  },
  {
    id: 9,
    name: 'Theatre',
    icon: '🎭',
    colorClass: 'bg-danger'
  },
  {
    id: 10,
    name: 'Events',
    icon: '🎉',
    colorClass: 'bg-info'
  }
]);

const scrollRef = ref(null)

const scroll = (direction) => {
  if (!scrollRef.value) return

  const el = scrollRef.value
  const amount = el.clientWidth / 2

  el.scrollTo({
    left: direction === 'left'
      ? el.scrollLeft - amount
      : el.scrollLeft + amount,
    behavior: 'smooth'
  })
}

</script>

<template>
  <div class="container my-5">

    <!-- Hero -->
    <div class="bg-warning text-dark p-5 rounded text-center mb-5">
      <h1 class="fw-bold">Exclusive Movie Premieres & Promos</h1>
      <p class="lead">
        Get your tickets now and enjoy 20% cashback on your first booking!
      </p>
    </div>
    
    <!-- diskon disskonn -->
     <h3 class="fw-bold mb-3">Vouchers and Deals</h3>
    <div class="position-relative deals-carousel">

      <!-- LEFT ARROW -->
      <button
        class="carousel-arrow left"
        @click="scroll('left')"
      >
        ‹
      </button>

      <!-- SCROLL AREA -->
      <div
        ref="scrollRef"
        class="d-flex gap-3 overflow-auto pb-3 deals-track"
      >
        <div
          v-for="deal in deals"
          :key="deal.id"
          class="deal-card card shadow-sm"
        >
          <div class="deal-image-wrapper">
            <img
              :src="deal.imageUrl"
              class="deal-image"
              :alt="deal.title"
            />
            <span class="deal-badge">
              {{ deal.discount }}
            </span>
          </div>

          <div class="card-body">
            <h5 class="fw-bold">{{ deal.title }}</h5>
            <p class="text-muted small mb-3">{{ deal.description }}</p>

            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted text-uppercase">Valid until 30 Dec</small>
              <a href="#" class="fw-bold text-primary text-decoration-none">
                Claim Deal →
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT ARROW -->
      <button
        class="carousel-arrow right"
        @click="scroll('right')"
      >
        ›
      </button>

    </div>

    <!-- find your tickets here -->
    <h3 class="fw-bold mb-3 mt-5">Find Your Tickets</h3>
    <div class="row g-4 justify-content-center">

      <div
        v-for="cat in categories"
        :key="cat.id"
        class="col-6 col-sm-4 col-md-2 text-center"
      >
        <button class="category-btn w-100">

          <div
            class="category-icon"
            :class="cat.colorClass"
          >
            <span class="icon-emoji">{{ cat.icon }}</span>
          </div>

          <span class="category-name">
            {{ cat.name }}
          </span>

        </button>
      </div>

    </div>

    <!-- Movies -->
    <h3 class="fw-bold mb-3">Now Playing</h3>

    <div class="row">
      <div class="col-md-3" v-for="movie in movies" :key="movie.id">
        <div class="card shadow-sm mb-4 movie-card">
          <div class="poster-wrapper">
            <img
              :src="movie.poster || '/images/posters/default.jpg'"
              class="movie-poster"
              alt="Movie Poster"
            />
          </div>

          <div class="card-body">
            <h6 class="fw-bold">{{ movie.title }}</h6>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>
