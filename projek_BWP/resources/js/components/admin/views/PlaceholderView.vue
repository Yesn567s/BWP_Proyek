<template>
  <section class="admin-section placeholder-section text-center py-5">
    <h4 class="fw-bold mb-2">{{ title }}</h4>
    <p class="text-muted mb-0">{{ message }}</p>
  </section>
</template>

<script setup>
const props = defineProps({
  title: { type: String, required: true },
  message: { type: String, default: 'Coming soon – modular workspace.' }
})
</script>
