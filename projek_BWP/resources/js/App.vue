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
import Navbar from './components/user/navbar.vue'
import Footer from './components/user/footer.vue'
</script>
