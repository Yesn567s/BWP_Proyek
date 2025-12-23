<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const activeTab = ref('active')
const showingQR = ref(null)
const tickets = ref([])

onMounted(async () => {
  const res = await axios.get('/api/tickets')
  tickets.value = res.data
})


const filteredTickets = computed(() => {
  return tickets.value.filter(t =>
    activeTab.value === 'active'
      ? t.status === 'active'
      : t.status !== 'active'
  )
})

// onMounted(async () => {
//   try {
//     const res = await axios.get('/api/my-tickets')
//     tickets.value = res.data
//   } catch (err) {
//     console.error('Failed to load tickets', err)
//   }
// })
</script>

<template>
  <div class="container py-5">

    <h1 class="fw-bold">Your Tickets</h1>
    <p class="text-muted mb-4">Manage and view your upcoming adventures</p>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <button
          class="nav-link"
          :class="{ active: activeTab === 'active' }"
          @click="activeTab = 'active'"
        >
          Active Tickets
        </button>
      </li>
      <li class="nav-item">
        <button
          class="nav-link"
          :class="{ active: activeTab === 'past' }"
          @click="activeTab = 'past'"
        >
          Past & Cancelled
        </button>
      </li>
    </ul>

    <!-- Ticket Cards -->
    <div v-if="filteredTickets.length" class="row g-4">
      <div
        v-for="ticket in filteredTickets"
        :key="ticket.id"
        class="col-12"
      >
        <div class="card rounded-4 shadow-sm">
          <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">

            <div class="d-flex gap-3 align-items-center">
              <div
                class="rounded-3 d-flex align-items-center justify-content-center fs-3"
                :class="ticket.status === 'active'
                  ? 'bg-primary bg-opacity-10 text-primary'
                  : 'bg-secondary bg-opacity-10 text-secondary'"
                style="width:64px;height:64px"
              >
                {{ getIcon(ticket.category_name) }}
              </div>

              <div>
                <small class="text-primary fw-bold">
                  {{ ticket.category_name }}
                </small>
                <h5 class="fw-bold mb-1">{{ ticket.title }}</h5>
                <small class="text-muted">
                  {{ ticket.date }} • {{ ticket.time }}
                </small>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button
                v-if="ticket.status === 'active'"
                class="btn btn-primary rounded-pill px-4"
                @click="showingQR = ticket.qr_value"
              >
                View QR
              </button>

              <button class="btn btn-light rounded-circle">⋮</button>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else class="text-center py-5">
      <div class="fs-1">🎫</div>
      <h4 class="fw-bold">No tickets found</h4>
    </div>

    <!-- QR Modal -->
    <div
      v-if="showingQR"
      class="modal fade show d-block"
      style="background: rgba(0,0,0,.6)"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 text-center">
          <button class="btn-close ms-auto" @click="showingQR = null"></button>

          <h4 class="fw-bold">Your Ticket QR</h4>

          <img
            :src="`https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${showingQR}`"
            class="img-fluid my-3"
          />

          <code>{{ showingQR }}</code>
        </div>
      </div>
    </div>

  </div>
</template>

