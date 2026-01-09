<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const activeTab = ref('active')
const showingQR = ref(null)
const currentTicket = ref(null)
const redeeming = ref(false)
const tickets = ref([])

onMounted(async () => {
  const res = await axios.get('/api/yourTickets')
  tickets.value = res.data
})

const filteredTickets = computed(() => {
  return tickets.value.filter(t =>
    activeTab.value === 'active'
      ? t.status === 'active'
      : t.status !== 'active'
  )
})

const formatDate = (dateStr) => {
  if (!dateStr) return 'N/A'
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}

const openQRModal = (ticket) => {
  showingQR.value = ticket.qr_value
  currentTicket.value = ticket
}

const closeQRModal = () => {
  showingQR.value = null
  currentTicket.value = null
}

const redeemTicket = async () => {
  if (!currentTicket.value || redeeming.value) return

  redeeming.value = true
  try {
    const res = await axios.post('/api/tickets/redeem', {
      qr_code: currentTicket.value.qr_value
    })

    if (res.data.success) {
      // Update the ticket status locally
      const ticketIndex = tickets.value.findIndex(t => t.qr_value === currentTicket.value.qr_value)
      if (ticketIndex !== -1) {
        tickets.value[ticketIndex].status = 'used'
      }
      alert('Ticket redeemed successfully!')
      closeQRModal()
    }
  } catch (err) {
    console.error('Redeem failed:', err)
    alert(err.response?.data?.error || 'Failed to redeem ticket')
  } finally {
    redeeming.value = false
  }
}
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
                <i class="bi bi-ticket-perforated"></i>
              </div>

              <div>
                <small class="text-primary fw-bold">
                  {{ ticket.category_name }}
                </small>
                <h5 class="fw-bold mb-1">{{ ticket.title }}</h5>
                <small class="text-muted d-block" v-if="ticket.valid_until">
                  <i class="bi bi-calendar-check me-1"></i>Valid until: {{ formatDate(ticket.valid_until) }}
                </small>
                <small class="text-muted d-block" v-if="ticket.order_date">
                  <i class="bi bi-bag-check me-1"></i>Ordered: {{ formatDate(ticket.order_date) }}
                </small>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button
                v-if="ticket.status === 'active'"
                class="btn btn-primary rounded-pill px-4"
                @click="openQRModal(ticket)"
              >
                View QR
              </button>

            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Empty -->
    <div v-if="!filteredTickets.length" class="text-center py-5">
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
          <button class="btn-close ms-auto" @click="closeQRModal"></button>

          <h4 class="fw-bold">Your Ticket QR</h4>

          <img
            :src="`https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${showingQR}`"
            class="img-fluid my-3"
          />

          <code class="d-block mb-3">{{ showingQR }}</code>

          <button 
            class="btn btn-success btn-lg rounded-pill px-5 fw-bold"
            :disabled="redeeming"
            @click="redeemTicket"
          >
            <span v-if="redeeming">Redeeming...</span>
            <span v-else><i class="bi bi-check-circle me-2"></i>Redeem Ticket</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

