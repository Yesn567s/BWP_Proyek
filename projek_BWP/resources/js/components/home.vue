<style scoped>
.movie-card {
  height: 520px;
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
  height: 600px;
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

.movie-desc {
  display: -webkit-box;
  -webkit-line-clamp: 2;      /* number of lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.trailer-card {
  width: 300px;
  cursor: pointer;
  flex-shrink: 0;
}

.trailer-thumb {
  position: relative;
  aspect-ratio: 16 / 9;
  overflow: hidden;
  border-radius: 16px;
}

.trailer-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.trailer-card:hover .trailer-img {
  transform: scale(1.05);
}

.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.35);
  display: flex;
  align-items: center;
  justify-content: center;
}

.play-btn {
  width: 56px;
  height: 56px;
  background: #dc3545;
  color: white;
  border-radius: 50%;
  font-size: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.duration {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(0,0,0,0.7);
  color: white;
  font-size: 10px;
  padding: 3px 6px;
  border-radius: 4px;
}

/* Title clamp */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Description clamp */
.trailer-desc {
  display: -webkit-box;
  -webkit-line-clamp: 2; /* show 2 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.trailer-card:hover .overlay {
  background: rgba(0,0,0,0.5);
}

.trailer-card:hover .play-btn {
  transform: scale(1.15);
}

.cat-icon{
  width: 50px;
  height: 50px;
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
    imageUrl: '/storage/posters/zootopia-movie.jpg'
  },
  {
    id: 2,
    title: 'Arcade Mania',
    description: 'Double credits for every top-up',
    discount: '2X CREDITS',
    imageUrl: '/storage/posters/zootopia-movie.jpg'
  },
  {
    id: 3,
    title: 'Night Show',
    description: 'Special price for late night movies',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia-movie.jpg'
  },
  {
    id: 4,
    title: 'tess',
    description: 'alohaa',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia-movie.jpg'
  },
  {
    id: 5,
    title: 'testistos',
    description: 'aaaaaaaa',
    discount: 'FLAT $5',
    imageUrl: '/images/posters/zootopia-movie.jpg'
  }
]);

const categories = ref([])

onMounted(async () => {
  const res = await axios.get('/api/categories')
  categories.value = res.data

  
})

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

const coomingSoon = ref([])

onMounted(() => {
  axios.get('/api/movies/now-playing').then(res => {
    movies.value = res.data
  })

  axios.get('/api/movies/upcoming').then(res => {
    coomingSoon.value = res.data
  })
})

const trailers = ref([])
const activeVideo = ref(null)

onMounted(() => {
  axios.get('/api/movies/trailers').then(res => {
    trailers.value = res.data
  })
})

const getYoutubeId = (url) => {
  const match = url.match(
    /(?:youtube\.com\/(?:.*v=|embed\/)|youtu\.be\/)([^&\/\?]+)/
  )
  return match ? match[1] : null
}

const openTrailer = (youtubeUrl) => {
  activeVideo.value = getYoutubeId(youtubeUrl)
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
      <button class="carousel-arrow left" @click="scroll('left')">‹</button>

      <!-- SCROLL AREA -->
      <div ref="scrollRef" class="d-flex gap-3 overflow-auto pb-3 deals-track">
        <div v-for="deal in deals" :key="deal.id" class="deal-card card shadow-sm">
          <div class="deal-image-wrapper">
            <img :src="deal.imageUrl" class="deal-image" :alt="deal.title"/>
            <span class="deal-badge">{{ deal.discount }}</span>
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
      <button class="carousel-arrow right" @click="scroll('right')">›</button>

    </div>

    <!-- find your tickets here -->
    <h3 class="fw-bold mb-3 mt-5">Find Your Tickets</h3>
    <div class="row g-4 justify-content-center">

      <div v-for="cat in categories":key="cat.id" class="col-6 col-sm-4 col-md-2 text-center">
        <button class="category-btn w-100">

          <div class="category-icon" :class="cat.colorClass">
             <img :src="`/${cat.icons}`" class="w-16 h-16 mb-2 cat-icon"/>
          </div>

          <span class="category-name">{{ cat.category_name }}</span>

        </button>
      </div>

    </div>

    <!-- Movies -->
    <!-- <h3 class="fw-bold mb-3">Now Playing</h3>

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
    </div> -->
    <h3 class="fw-bold mb-3">Now Playing</h3>

    <div class="position-relative deals-carousel">
      <!-- LEFT ARROW -->
      <button class="carousel-arrow left" @click="scroll('left')">‹</button>
      <!-- SCROLL AREA -->
      <div ref="scrollRef" class="d-flex gap-3 overflow-auto pb-3 deals-track"
      >
        <div class="col-md-3" v-for="movie in movies" :key="movie.id">
          <div class="card shadow-sm mb-4 movie-card">
            <div class="poster-wrapper">
            <img :src="movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Movie Poster"
            />
          </div>

          <div class="card-body">
            <h6 class="fw-bold">{{ movie.title }}</h6>
            <p class="text-muted small mb-3 movie-desc">{{ movie.desc }}</p>
          </div>
          </div>
        </div>
      </div>

      <!-- RIGHT ARROW -->
      <button class="carousel-arrow right" @click="scroll('right')">›</button>
    </div>

    <!-- upcoming movies -->
    <h3 class="fw-bold mb-3 mt-5">Upcoming Movies</h3>

    <div class="position-relative deals-carousel">

      <!-- LEFT -->
      <button class="carousel-arrow left" @click="scroll('left')">‹</button>

      <!-- SCROLL -->
      <div ref="scrollRef" class="d-flex gap-3 overflow-auto pb-3 deals-track">
        <div class="col-md-3" v-for="movie in coomingSoon":key="movie.product_id">
          <div class="card shadow-sm mb-4 movie-card">
            <div class="poster-wrapper">
              <img :src="movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Movie Poster"/>
            </div>

            <div class="card-body">
              <h6 class="fw-bold">{{ movie.title }}</h6>
              <p class="text-muted small movie-desc">{{ movie.desc }}</p>

              <!-- RELEASE DATE -->
              <small class="text-primary fw-bold">📅: {{ new Date(movie.release_date).toLocaleDateString() }}</small>
            </div>

          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <button class="carousel-arrow right" @click="scroll('right')">›</button>
    </div>
  </div>

  <!-- trailer -->
<div class="container my-5">
  <h3 class="fw-bold mb-3 mt-5">Latest Trailers</h3>

  <div class="d-flex gap-4 overflow-auto pb-3">
    <div v-for="movie in trailers" :key="movie.id" class="trailer-card" @click="openTrailer(movie.youtube_url)">
      <div class="trailer-thumb">
        <img :src="`https://img.youtube.com/vi/${getYoutubeId(movie.youtube_url)}/hqdefault.jpg`" class="trailer-img"/>

        <div class="overlay">
          <div class="play-btn">▶</div>
        </div>
      </div>

      <h6 class="fw-bold mt-2 line-clamp-1">{{ movie.title }}</h6>
      <p class="text-muted small trailer-desc">{{ movie.description }}</p>
    </div>
  </div>
</div>


</template>
