<template>
  <div>
    <Navbar />
    <main>
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from './components/navbar.vue'
import Footer from './components/footer.vue'
</script>
