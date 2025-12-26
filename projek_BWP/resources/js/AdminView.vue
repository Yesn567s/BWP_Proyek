<template>
  <div>
    <Navbar v-if="!isAdmin" />
    <main>
      <router-view />
    </main>
    <Footer v-if="!isAdmin" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const user = ref(null)

onMounted(() => {
  const el = document.getElementById('app')
  if (el?.dataset?.user) {
    try { user.value = JSON.parse(el.dataset.user) } catch (e) { user.value = null }
  }
})

const isAdmin = computed(() => user.value?.role === 'admin')
</script>
