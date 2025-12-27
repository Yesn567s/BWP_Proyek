<style scoped>
.menu-btn {
  transition: all 0.25s ease;
}

.menu-btn:hover {
  border-color: #0d6efd;
  box-shadow: 0 8px 24px rgba(13,110,253,0.15);
}

.icon-box {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
}

.logout-btn:hover {
  background-color: #dc3545 !important;
  color: white !important;
}
</style>

<script setup>
import { reactive } from 'vue'

const CURRENT_USER = reactive({
  name: 'John Doe',
  email: 'john@example.com',
  avatar: 'https://i.pravatar.cc/300',
  loyaltyPoints: 1200,
  memberSince: '2023'
})

const menuItems = [
  { icon: '👤', title: 'Personal Information', sub: 'Name, email, and phone number' },
  { icon: '🛡️', title: 'Security', sub: 'Password and two-factor auth' },
  { icon: '💳', title: 'Payment Methods', sub: 'Cards and digital wallets' },
  { icon: '🔔', title: 'Notifications', sub: 'Email and push alerts' },
  { icon: '🌍', title: 'Language', sub: 'English (US)' },
  { icon: '🎁', title: 'Promos & Vouchers', sub: '3 active vouchers available' }
]
</script>

<template>
  <div class="container my-5" style="max-width: 900px">

    <!-- PROFILE CARD -->
    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5 mb-5 position-relative overflow-hidden">
      <div class="position-absolute top-0 end-0 bg-primary opacity-10 rounded-bottom-circle"
           style="width:130px;height:130px;"></div>

      <div class="d-flex flex-column flex-md-row align-items-center gap-4">

        <!-- AVATAR -->
        <div class="position-relative">
          <img
            :src="CURRENT_USER.avatar"
            class="rounded-circle border border-4 border-white shadow"
            style="width:120px;height:120px;object-fit:cover"
          />

          <button
            class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0"
          >
            ✏️
          </button>
        </div>

        <!-- USER INFO -->
        <div class="text-center text-md-start flex-grow-1">
          <h2 class="fw-bold mb-1">{{ CURRENT_USER.name }}</h2>
          <p class="text-muted mb-3">{{ CURRENT_USER.email }}</p>

          <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3">
            <div class="bg-primary text-white px-3 py-2 rounded-3 fw-bold small">
              ✨ {{ CURRENT_USER.loyaltyPoints }} Points
            </div>
            <div class="bg-light px-3 py-2 rounded-3 fw-bold small text-muted">
              Member since {{ CURRENT_USER.memberSince }}
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- MENU GRID -->
    <div class="row g-3 mb-4">
      <div class="col-md-6" v-for="(item, i) in menuItems" :key="i">
        <button class="w-100 text-start p-4 rounded-4 border bg-white d-flex align-items-center gap-3 menu-btn">
          <div class="icon-box">
            {{ item.icon }}
          </div>

          <div>
            <h6 class="fw-bold mb-0">{{ item.title }}</h6>
            <small class="text-muted">{{ item.sub }}</small>
          </div>

          <span class="ms-auto text-muted fs-5">›</span>
        </button>
      </div>
    </div>

    <!-- LOGOUT -->
    <button class="btn btn-danger bg-opacity-10 text-danger w-100 py-3 fw-bold rounded-4 logout-btn">
      Log Out Account
    </button>

  </div>
</template>

