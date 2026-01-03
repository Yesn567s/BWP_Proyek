<style scoped>
*{
    box-sizing: border-box;
}

.blog-container {
  /* background-color: #f8f9fa; */
  min-height: 100vh;
  padding-left: 80px;
  padding-right: 80px;
}

.featured-blog {
  margin-bottom: 40px;
}

.featured-image {
  width: 100%;
  overflow: hidden;
  border-radius: 8px;
}

.featured-image img {
  width: 100%;
  height: 350px;
  object-fit: cover;
}

.category-badge {
  display: inline-block;
  color: #007bff;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.featured-content h2 {
  font-size: 28px;
  line-height: 1.3;
  margin-top: 15px;
}

.meta-info {
  font-size: 13px;
}

.blog-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.blog-card:hover {
  transform: translateY(-5px);
}

.blog-image {
  width: 100%;
  height: 220px;
  overflow: hidden;
  border-radius: 8px 8px 0 0;
}

.blog-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.blog-card-content {
  padding: 15px;
}

.blog-card-content h5 {
  font-size: 16px;
  line-height: 1.4;
}

.sidebar {
  position: sticky;
  top: 20px;
}

.sidebar h4 {
  font-size: 18px;
  padding-bottom: 15px;
}

.sidebar-divider {
  height: 2px;
  background: #333;
  width: 50px;
}

.news-item {
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: 15px;
}

.news-item h6 {
  font-size: 14px;
  line-height: 1.4;
  margin-bottom: 8px;
}

.news-item:last-child {
  border-bottom: none;
}
/* ---------------------------------- */

.hero {
  width: 100vw;
  height: 500px;
  margin: auto;
  justify-content: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

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
  width: 45px;
  height: 45px;
  background: black;
  opacity: 0.7;
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
import { useRouter } from 'vue-router'

const router = useRouter()

const movies = ref([])

onMounted(() => {
  axios.get('/api/movies').then(res => {
    console.log(res.data[0])
    movies.value = res.data
  })
})

const deals = ref([])

onMounted(async () => {
  const res = await axios.get('/api/vouchers')

  deals.value = res.data.map(voucher => ({
    id: voucher.voucher_id,
    title: voucher.title,
    description: voucher.description,
    discount:
      voucher.discount_type === 'percent'
        ? `${voucher.discount_value}% OFF`
        : `FLAT ${voucher.discount_value}`,
    imageUrl: '/images/posters/zootopia-movie.jpg', // static for now
    validUntil: voucher.end_date
  }))
})

const categories = ref([])
const goToCategory = (cat) => {
  const name = cat.category_name.toLowerCase()

  if (name.includes('movie')) {
    router.push({ name: 'movies' })
  } 
  else if (name.includes('food')) {
    router.push({ name: 'food' })
  } 
  else {
    router.push({ name: 'fun' })
  }
}


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

const blogs = ref([]);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(async () => {
  try {
    const res = await axios.get('/api/blogs');
    // Sort by newest first
    blogs.value = res.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  } catch (error) {
    console.error('Error fetching blogs:', error);
  }
});

</script>

<template>
  <!-- Hero -->
  <div class="hero bg-warning text-dark p-5 rounded text-center mb-5">
      <h1 class="fw-bold">Exclusive Movie Premieres & Promos</h1>
      <p class="lead">
        Get your tickets now and enjoy 20% cashback on your first booking!
      </p>
    </div>
  <div class="container my-5">
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

      <div v-for="cat in categories" :key="cat.id" class="col-6 col-sm-4 col-md-2 text-center">
        <button
  class="category-btn w-100 text-decoration-none"
  @click="goToCategory(cat)"
>
  <div class="category-icon">
    <img :src="`/${cat.icons}`" class="cat-icon"/>
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
            <img :src="movie.poster || '/images/posters/default.jpg'" class="movie-poster" alt="Movie Poster"/>
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
          <div class="play-btn"><i class="bi bi-play-fill" style="opacity: 1;"></i></div>
        </div>
      </div>

      <h6 class="fw-bold mt-2 line-clamp-1">{{ movie.title }}</h6>
      <p class="text-muted small trailer-desc">{{ movie.description }}</p>
    </div>
  </div>
</div>

<!-- blog -->
 <div class="blog-container">
    <div class="container-fluid px-4 py-5">
      <h3 class="fw-bold mb-3 mt-5">Blogs</h3>
      <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
          <div v-if="blogs.length === 0" class="text-center text-muted py-5">
            No blogs available.
          </div>
          <div v-else>
            <!-- Featured Blog -->
            <!-- <div v-if="blogs[0]" class="featured-blog mb-5">
              <div class="featured-image">
                <img :src="blogs[0].image || 'https://media.tenor.com/Ym6VeAcZoTcAAAAm/aaaah-cat.webp'" :alt="blogs[0].title" class="img-fluid rounded">
              </div>
              <div class="featured-content mt-3">
                <span class="category-badge">{{ blogs[0].category || 'BLOG' }}</span>
                <h2 class="fw-bold mt-2">{{ blogs[0].title }}</h2>
                <div class="meta-info text-muted small mt-2">
                  <span>by {{ blogs[0].author || 'Admin' }}</span>
                  <span class="ms-3">{{ formatDate(blogs[0].created_at) }}</span>
                </div>
                <p class="mt-3">{{ blogs[0].excerpt || blogs[0].content.substring(0, 150) + '...' }}</p>
              </div>
            </div> -->

            <!-- Grid Blogs -->
            <div class="row g-4" style="text-decoration: none;">
              <div v-for="(blog, index) in blogs.slice(0)" :key="blog.id" class="col-md-6">
                <router-link :to="{ path: '/blog', query: { id: blog.post_id } }" style="text-decoration: none; color: inherit;">
                <div class="blog-card">
                  <div class="blog-image">
                    <img :src="blog.image || 'https://media.tenor.com/Ym6VeAcZoTcAAAAm/aaaah-cat.webp'" :alt="blog.title" class="img-fluid rounded">
                  </div>
                  <div class="blog-card-content mt-3">
                    <span class="category-badge">{{ blog.category || 'BLOG' }}</span>
                    <h5 class="fw-bold mt-2">{{ blog.title }}</h5>
                    <div class="meta-info text-muted small mt-2">
                      <span>by {{ blog.author || 'Admin' }}</span>
                      <span class="ms-3">{{ formatDate(blog.created_at) }}</span>
                    </div>
                    <p class="mt-2 text-muted small">{{ blog.excerpt || blog.content.substring(0, 80) + '...' }}</p>
                  </div>
                </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="sidebar">
            <h4 class="fw-bold mb-4">MORE NEWS</h4>
            <div class="sidebar-divider mb-4"></div>
            <div v-if="blogs.length === 0" class="text-muted">
              No news available.
            </div>
            <div v-else class="news-list">
              <div v-for="blog in blogs.slice(0, 6)" :key="blog.id" class="news-item mb-4 pb-4">
                <h6 class="fw-bold">{{ blog.title }}</h6>
                <small class="text-muted">{{ formatDate(blog.created_at) }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
