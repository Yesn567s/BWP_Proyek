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
    
    <!-- diskon -->
     <h3 class="fw-bold mb-3">Vouchers and Deals</h3>
     
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
