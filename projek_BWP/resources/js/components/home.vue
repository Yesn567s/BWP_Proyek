<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const movies = ref([])

onMounted(() => {
  axios.get('/api/movies')
    .then(res => {
      movies.value = res.data
      console.log(res.data)
    })
    .catch(err => console.error(err))
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
    
    <!-- Movies -->
    <h3 class="fw-bold mb-3">Upcoming Movies</h3>

    <div class="row">
      <div class="col-md-3" v-for="movie in movies" :key="movie.product_id">
        <div class="card shadow-sm mb-4">
          <img
            :src="movie.poster_url"
            class="card-img-top"
            alt="Movie poster"
          />
          <div class="card-body">
            <h6 class="fw-bold">{{ movie.name }}</h6>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
