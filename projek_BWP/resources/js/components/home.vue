<template>
  <div class="container my-5">

    <!-- Hero -->
    <div class="bg-warning text-dark p-5 rounded text-center mb-5">
      <h1 class="fw-bold">Exclusive Movie Premieres & Promos</h1>
      <p class="lead">
        Get your tickets now and enjoy 20% cashback on your first booking!
      </p>
    </div>

    <!-- Deals -->
    <h3 class="fw-bold mb-3">Best Deals</h3>
    <div class="row mb-5">
      <div class="col-md-4" v-for="deal in deals" :key="deal.id">
        <div class="card shadow-sm mb-3">
          <div class="card-body">
            <span class="badge bg-info mb-2">{{ deal.discount }}</span>
            <h5 class="fw-bold">{{ deal.title }}</h5>
            <p class="text-muted mb-1">Code: {{ deal.code }}</p>
            <small>Valid until {{ deal.expiry }}</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <h3 class="fw-bold mb-3">Find Tickets</h3>
    <div class="row text-center mb-5">
      <div class="col-4 col-md-2 mb-3" v-for="cat in categories" :key="cat.id">
        <div class="border rounded p-3 fw-bold">
          {{ cat.name }}
        </div>
      </div>
    </div>

    <!-- Movies -->
    <h3 class="fw-bold mb-3">Upcoming Movies</h3>
    <div class="row">
      <div class="col-md-3" v-for="movie in movies" :key="movie.id">
        <div class="card shadow-sm mb-4">
          <img :src="movie.image" class="card-img-top" />
          <div class="card-body">
            <h6 class="fw-bold">{{ movie.title }}</h6>
            <span class="badge bg-warning text-dark">⭐ {{ movie.rating }}</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
// import { DEALS, CATEGORIES, MOVIES } from '../constants'

// const deals = DEALS
// const categories = CATEGORIES
// const movies = MOVIES
</script>
