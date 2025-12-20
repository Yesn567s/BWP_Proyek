<template>
  <div>
    <Navbar />
    <Home />
    <!-- <h1>Hello from Vue 3 + Laravel 12 + Vite</h1> -->
    <ExampleComponent />
    <Footer />
  </div>
</template>

<script setup>
import Navbar from './components/navbar.vue'
import Home from './components/home.vue'
import Footer from './components/footer.vue'
import ExampleComponent from './components/ExampleComponent.vue'
</script>
