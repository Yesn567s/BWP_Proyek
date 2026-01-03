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
  color: black !important;
}

.hover-shadow:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  transition: all 0.25s ease;
  cursor: pointer;
}

</style>

<script setup>
import { reactive, onMounted, ref, computed } from 'vue'
import axios from 'axios'

// Redirect to login if session not present (use full-page navigation)
onMounted(async () => {
  try {
    const res = await axios.get('/current-user')
    if (!res.data || !res.data.authenticated) {
      window.location.href = '/login'
    }
  } catch (e) {
    window.location.href = '/login'
  }
})

// hidden file input ref
const avatarInput = ref(null)

// handle click on pencil
const triggerAvatarUpload = () => {
  avatarInput.value.click()
}

// handle image selection
const onAvatarChange = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  if (!file.type.startsWith('image/')) {
    alert('Please select a valid image')
    return
  }

  if (file.size > 2 * 1024 * 1024) {
    alert('Image must be under 2MB')
    return
  }

  // Preview immediately
  const reader = new FileReader()
  reader.onload = () => {
    CURRENT_USER.avatar = reader.result
  }
  reader.readAsDataURL(file)

  // Upload to backend
  const formData = new FormData()
  formData.append('avatar', file)

  try {
    const res = await axios.post('/api/profile/avatar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    // Replace preview with stored image path
    CURRENT_USER.avatar = '/' + res.data.path

  } catch (err) {
    console.error(err)
    alert('Failed to upload avatar')
  }
}

// const CURRENT_USER = reactive({
//   name: 'John Doe',
//   email: 'john@example.com',
//   avatar: 'https://i.pravatar.cc/300',
//   loyaltyPoints: 1200,
//   memberSince: '2023'
// })

const CURRENT_USER = reactive({
  name: '',
  email: '',
  phone: '',
  avatar: '',
  loyaltyPoints: 0,
  memberSince: ''
})

// Fetch logged-in user
onMounted(async () => {
  try {
    const res = await axios.get('/api/profile')

    CURRENT_USER.name = res.data.name
    CURRENT_USER.email = res.data.email
    CURRENT_USER.phone = res.data.phone_number
    CURRENT_USER.loyaltyPoints = res.data.points
    CURRENT_USER.memberSince = new Date(res.data.member_start).getFullYear()

    CURRENT_USER.avatar = res.data.profile_picture
      ? `/${res.data.profile_picture}`
      : 'https://i.pravatar.cc/300'

    // Initialize form
    formPersonal.name = CURRENT_USER.name
    formPersonal.email = CURRENT_USER.email
    formPersonal.phone = CURRENT_USER.phone

  } catch (err) {
    alert('You are not logged in')
    window.location.href = '/login'
  }
})

const menuItems = [
  { key: 'personal', icon: '👤', title: 'Personal Information', sub: 'Name, email, and phone number' },
  { key: 'security', icon: '🛡️', title: 'Security', sub: 'Password and two-factor auth' },
  { key: 'payment', icon: '💳', title: 'Payment Methods', sub: 'Cards and digital wallets' },
  { key: 'notifications', icon: '🔔', title: 'Notifications', sub: 'Email and push alerts' },
  { key: 'language', icon: '🌍', title: 'Language', sub: 'English (US)' },
  { key: 'promo', icon: '🎁', title: 'Promos & Vouchers', sub: '3 active vouchers available' }
]


const activeMenu = ref(null)

// PERSONAL INFORMATION FORM
const formPersonal = reactive({
  name: CURRENT_USER.name,
  email: CURRENT_USER.email,
  phone: CURRENT_USER.phone
})

const savePersonalInfo = async () => {
  try {
    const res = await axios.post('/api/profile/personal', {
      name: formPersonal.name,
      email: formPersonal.email,
      phone: formPersonal.phone
    })

    // Update local user state
    CURRENT_USER.name = formPersonal.name
    CURRENT_USER.email = formPersonal.email
    CURRENT_USER.phone = formPersonal.phone

    alert('Personal information updated successfully!')

  } catch (err) {
    if (err.response?.status === 409) {
      alert('Email already in use')
    } else {
      alert('Failed to update profile')
    }
  }
}

// PAYMENT METHODS (for demonstration)
const paymentMethods = reactive([
  // {
  //   id: 1,
  //   type: 'Visa',
  //   last4: '4242',
  //   expiry: '08/28',
  //   isDefault: true
  // },
  // {
  //   id: 2,
  //   type: 'Mastercard',
  //   last4: '1881',
  //   expiry: '11/27',
  //   isDefault: false
  // }
])

const setDefaultPayment = (id) => {
  paymentMethods.forEach(p => (p.isDefault = p.id === id))
}

const removePayment = (id) => {
  const index = paymentMethods.findIndex(p => p.id === id)
  if (index !== -1) paymentMethods.splice(index, 1)
}

const showAddPayment = ref(false)

const newPayment = reactive({
  type: 'Visa',
  cardNumber: '',
  expiry: '',
  name: ''
})

const addPaymentMethod = () => {
  if (!newPayment.cardNumber || !newPayment.expiry || !newPayment.name) {
    alert('Please complete all fields')
    return
  }

  const last4 = newPayment.cardNumber.slice(-4)

  paymentMethods.push({
    id: Date.now(),
    type: newPayment.type,
    last4,
    expiry: newPayment.expiry,
    isDefault: paymentMethods.length === 0
  })

  // reset form
  newPayment.type = 'Visa'
  newPayment.cardNumber = ''
  newPayment.expiry = ''
  newPayment.name = ''

  showAddPayment.value = false
}

// LANGUAGE SETTINGS
const availableLanguages = [
  { code: 'en', label: 'English (US)', flag: '🇺🇸' },
  { code: 'id', label: 'Bahasa Indonesia', flag: '🇮🇩' },
  { code: 'jp', label: '日本語 (Japanese)', flag: '🇯🇵' },
  { code: 'fr', label: 'Français (French)', flag: '🇫🇷' }
]

// Load saved language or default
const selectedLanguage = ref(
  localStorage.getItem('app_language') || 'en'
)

const saveLanguage = () => {
  localStorage.setItem('app_language', selectedLanguage.value)

  // Reload UI to apply changes
  window.location.reload()
}

// SECURITY SETTINGS
const securityForm = reactive({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
  twoFactorEnabled: false
})

const showPassword = reactive({
  current: false,
  new: false,
  confirm: false
})

const passwordStrength = computed(() => {
  const p = securityForm.newPassword
  if (p.length < 6) return { label: 'Weak', color: 'danger' }
  if (p.match(/[A-Z]/) && p.match(/[0-9]/) && p.length >= 8)
    return { label: 'Strong', color: 'success' }
  return { label: 'Medium', color: 'warning' }
})

const saveSecuritySettings = () => {
  if (securityForm.newPassword !== securityForm.confirmPassword) {
    alert('New password does not match')
    return
  }

  alert('Security settings updated successfully')

  // Reset form
  securityForm.currentPassword = ''
  securityForm.newPassword = ''
  securityForm.confirmPassword = ''
}

// NOTIFICATION SETTINGS
const notificationSettings = reactive({
  upcomingMovie: {
    email: true,
    push: true
  },
  newProduct: {
    email: true,
    push: false
  },
  events: {
    email: false,
    push: true
  },
  payments: {
    email: true,
    push: true
  }
})

const saveNotificationSettings = () => {
  // Example: send to backend
  // axios.post('/user/notifications', notificationSettings)

  alert('Notification preferences saved successfully!')
}

// PROMOS & VOUCHERS
const promoVouchers = reactive([
  {
    id: 1,
    title: '50% Off Movie Tickets',
    description: 'Valid for all movies on weekdays',
    code: 'MOVIE50',
    expiry: '30 Jun 2025',
    type: 'discount',
    used: false
  },
  {
    id: 2,
    title: 'Free Popcorn Combo',
    description: 'Get a free popcorn with any ticket purchase',
    code: 'POPCORNFREE',
    expiry: '15 Jul 2025',
    type: 'freebie',
    used: false
  },
  {
    id: 3,
    title: 'Cashback Rp25.000',
    description: 'Cashback for payments using e-wallet',
    code: 'CASHBACK25',
    expiry: '31 Aug 2025',
    type: 'cashback',
    used: true
  }
])

const copyPromoCode = (code) => {
  navigator.clipboard.writeText(code)
  alert(`Promo code "${code}" copied!`)
}

// Logout: call backend to destroy session, clear client storage, then redirect
const logout = async () => {
  try {
    // Laravel default logout route
    await axios.post('/logout')
  } catch (e) {
    // ignore network/server errors and continue clearing client state
    console.warn('logout request failed', e)
  }

  // Clear any client-side auth/session data (if used)
  try {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('current_user')
    // remove any other app-specific keys
    Object.keys(localStorage).forEach(k => {
      if (k.startsWith('app_') || k.startsWith('user_')) localStorage.removeItem(k)
    })
  } catch (e) {
    // ignore
  }

  // Redirect to login (full page navigation ensures session cookie change is applied)
  window.location.href = '/login'
}

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
            class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" @click="triggerAvatarUpload"
          >
            ✏️
          </button>

          <!-- HIDDEN FILE INPUT -->
          <input
            type="file"
            accept="image/*"
            ref="avatarInput"
            class="d-none"
            @change="onAvatarChange"
          />
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
    <div class="row g-3 mb-5">
      <div class="col-md-6" v-for="item in menuItems" :key="item.key">
        <button
          class="w-100 text-start p-4 rounded-4 border bg-white d-flex align-items-center gap-3 menu-btn"
          :class="{ 'border-primary shadow-sm': activeMenu === item.key }"
          @click="activeMenu = item.key"
        >
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

    <!-- CONTENT PANEL -->
    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">

      <div v-if="!activeMenu" class="text-center py-5">
        <h5 class="fw-bold mb-2">Select a menu</h5>
        <p class="text-muted">Choose an option to view account settings</p>
      </div>

      <!-- PERSONAL INFORMATION -->
      <div v-else-if="activeMenu === 'personal'">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="fw-bold mb-1">Personal Information</h4>
            <p class="text-muted mb-0">Update your personal details here</p>
          </div>
          <button class="btn btn-primary rounded-pill px-4" @click="savePersonalInfo">
            Save Changes
          </button>
        </div>

        <div class="row g-4">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Full Name</label>
            <input
              type="text"
              class="form-control form-control-lg rounded-4"
              v-model="formPersonal.name"
            />
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Email Address</label>
            <input
              type="email"
              class="form-control form-control-lg rounded-4"
              v-model="formPersonal.email"
            />
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Phone Number</label>
            <input
              type="text"
              class="form-control form-control-lg rounded-4"
              v-model="formPersonal.phone"
            />
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Member Since</label>
            <input
              type="text"
              class="form-control form-control-lg rounded-4 bg-light"
              :value="CURRENT_USER.memberSince"
              disabled
            />
          </div>
        </div>
      </div>

      <!-- PAYMENT METHODS -->
      <div v-else-if="activeMenu === 'payment'">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="fw-bold mb-1">Payment Methods</h4>
            <p class="text-muted mb-0">
              Manage your saved cards and digital wallets
            </p>
          </div>
          <button class="btn btn-outline-primary rounded-pill px-4" @click="showAddPayment = true">
            + Add Payment Method
          </button>
        </div>

        <div class="row g-4">
          <div
            class="col-md-6"
            v-for="method in paymentMethods"
            :key="method.id"
          >
            <div
              class="border rounded-4 p-4 h-100 position-relative"
              :class="method.isDefault ? 'border-primary shadow-sm' : ''"
            >
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box">
                  💳
                </div>

                <div>
                  <h6 class="fw-bold mb-0">
                    {{ method.type }} •••• {{ method.last4 }}
                  </h6>
                  <small class="text-muted">
                    Expiry {{ method.expiry }}
                  </small>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button
                  v-if="!method.isDefault"
                  class="btn btn-sm btn-outline-primary rounded-pill"
                  @click="setDefaultPayment(method.id)"
                >
                  Set as Default
                </button>

                <span
                  v-else
                  class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"
                >
                  Default
                </span>

                <button
                  class="btn btn-sm btn-outline-danger rounded-pill ms-auto"
                  @click="removePayment(method.id)"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- EMPTY STATE -->
        <div
          v-if="paymentMethods.length === 0"
          class="text-center py-5 text-muted"
        >
          <h6 class="fw-bold mb-2">No payment methods</h6>
          <p>Add a card or wallet to make checkout faster</p>
        </div>
      </div>

      <!-- LANGUAGE -->
      <div v-else-if="activeMenu === 'language'">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="fw-bold mb-1">Language</h4>
            <p class="text-muted mb-0">
              Choose your preferred display language
            </p>
          </div>

          <button
            class="btn btn-primary rounded-pill px-4"
            @click="saveLanguage"
          >
            Save Changes
          </button>
        </div>

        <div class="row g-3">
          <div
            class="col-md-6"
            v-for="lang in availableLanguages"
            :key="lang.code"
          >
            <label
              class="border rounded-4 p-4 d-flex align-items-center gap-3 cursor-pointer"
              :class="selectedLanguage === lang.code
                ? 'border-primary shadow-sm'
                : 'hover-shadow'"
            >
              <input
                type="radio"
                class="form-check-input"
                :value="lang.code"
                v-model="selectedLanguage"
              />

              <div class="fs-3">
                {{ lang.flag }}
              </div>

              <div>
                <h6 class="fw-bold mb-0">{{ lang.label }}</h6>
                <small class="text-muted">
                  {{ lang.code.toUpperCase() }}
                </small>
              </div>

              <span
                v-if="selectedLanguage === lang.code"
                class="ms-auto badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2"
              >
                Active
              </span>
            </label>
          </div>
        </div>
      </div>

      <!-- SECURITY SETTINGS -->
      <div v-else-if="activeMenu === 'security'">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="fw-bold mb-1">Security</h4>
            <p class="text-muted mb-0">
              Manage your password and account protection
            </p>
          </div>

          <button
            class="btn btn-primary rounded-pill px-4"
            @click="saveSecuritySettings"
          >
            Save Changes
          </button>
        </div>

        <!-- CHANGE PASSWORD -->
        <div class="border rounded-4 p-4 mb-4">
          <h6 class="fw-bold mb-3">Change Password</h6>

          <div class="row g-3">

            <!-- CURRENT PASSWORD -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Current Password</label>
              <div class="input-group">
                <input
                  :type="showPassword.current ? 'text' : 'password'"
                  class="form-control rounded-start-4"
                  v-model="securityForm.currentPassword"
                />
                <button
                  class="btn btn-light"
                  @click="showPassword.current = !showPassword.current"
                >
                  👁️
                </button>
              </div>
            </div>

            <!-- NEW PASSWORD -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">New Password</label>
              <div class="input-group">
                <input
                  :type="showPassword.new ? 'text' : 'password'"
                  class="form-control rounded-start-4"
                  v-model="securityForm.newPassword"
                />
                <button
                  class="btn btn-light"
                  @click="showPassword.new = !showPassword.new"
                >
                  👁️
                </button>
              </div>

              <small
                class="fw-bold mt-1 d-inline-block"
                :class="`text-${passwordStrength.color}`"
              >
                Strength: {{ passwordStrength.label }}
              </small>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Confirm New Password</label>
              <div class="input-group">
                <input
                  :type="showPassword.confirm ? 'text' : 'password'"
                  class="form-control rounded-start-4"
                  v-model="securityForm.confirmPassword"
                />
                <button
                  class="btn btn-light"
                  @click="showPassword.confirm = !showPassword.confirm"
                >
                  👁️
                </button>
              </div>
            </div>

          </div>
        </div>

        <!-- TWO FACTOR AUTH -->
        <div class="border rounded-4 p-4 mb-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="fw-bold mb-1">Two-Factor Authentication (2FA)</h6>
              <p class="text-muted mb-0">
                Add an extra layer of security to your account
              </p>
            </div>

            <div class="form-check form-switch">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="securityForm.twoFactorEnabled"
              />
            </div>
          </div>

          <div
            v-if="securityForm.twoFactorEnabled"
            class="alert alert-success mt-3 rounded-4"
          >
            ✅ Two-factor authentication is enabled
          </div>
        </div>

        <!-- SECURITY INFO -->
        <div class="bg-light rounded-4 p-4">
          <h6 class="fw-bold mb-2">Security Tips</h6>
          <ul class="text-muted mb-0">
            <li>Use a strong, unique password</li>
            <li>Never share your login credentials</li>
            <li>Enable 2FA for maximum protection</li>
          </ul>
        </div>

      </div>

      <!-- NOTIFICATION SETTINGS -->
      <div v-else-if="activeMenu === 'notifications'">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h4 class="fw-bold mb-1">Notifications</h4>
            <p class="text-muted mb-0">
              Choose how you want to receive updates
            </p>
          </div>

          <button
            class="btn btn-primary rounded-pill px-4"
            @click="saveNotificationSettings"
          >
            Save Changes
          </button>
        </div>

        <!-- NOTIFICATION LIST -->
        <div class="border rounded-4 divide-y">

          <!-- UPCOMING MOVIES -->
          <div class="p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="fw-bold mb-0">🎬 Upcoming Movies</h6>
                <small class="text-muted">
                  Get notified about upcoming movies and premieres
                </small>
              </div>
              <div class="d-flex gap-3">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.upcomingMovie.email"
                  />
                  <label class="form-check-label small">Email</label>
                </div>
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.upcomingMovie.push"
                  />
                  <label class="form-check-label small">Push</label>
                </div>
              </div>
            </div>
          </div>

          <!-- NEW PRODUCTS -->
          <div class="p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="fw-bold mb-0">🛍️ New Products</h6>
                <small class="text-muted">
                  Snacks, merchandise, and exclusive items
                </small>
              </div>
              <div class="d-flex gap-3">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.newProduct.email"
                  />
                  <label class="form-check-label small">Email</label>
                </div>
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.newProduct.push"
                  />
                  <label class="form-check-label small">Push</label>
                </div>
              </div>
            </div>
          </div>

          <!-- EVENTS -->
          <div class="p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="fw-bold mb-0">🎟️ Events</h6>
                <small class="text-muted">
                  Special events, premieres, and live shows
                </small>
              </div>
              <div class="d-flex gap-3">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.events.email"
                  />
                  <label class="form-check-label small">Email</label>
                </div>
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.events.push"
                  />
                  <label class="form-check-label small">Push</label>
                </div>
              </div>
            </div>
          </div>

          <!-- PAYMENTS -->
          <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="fw-bold mb-0">💳 Payments</h6>
                <small class="text-muted">
                  Receipts, payment confirmations, and refunds
                </small>
              </div>
              <div class="d-flex gap-3">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.payments.email"
                  />
                  <label class="form-check-label small">Email</label>
                </div>
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="notificationSettings.payments.push"
                  />
                  <label class="form-check-label small">Push</label>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- FOOTNOTE -->
        <div class="alert alert-light rounded-4 mt-4">
          🔔 You can change notification preferences at any time.
          Critical payment alerts will always be delivered.
        </div>

      </div>

      <!-- PROMOS & VOUCHERS -->
      <div v-else-if="activeMenu === 'promo'">

        <!-- HEADER -->
        <div class="mb-4">
          <h4 class="fw-bold mb-1">Promos & Vouchers</h4>
          <p class="text-muted mb-0">
            View and use your available promotions
          </p>
        </div>

        <!-- PROMO LIST -->
        <div class="row g-4">

          <div
            class="col-md-6"
            v-for="promo in promoVouchers"
            :key="promo.id"
          >
            <div
              class="border rounded-4 p-4 h-100 position-relative"
              :class="promo.used ? 'bg-light text-muted' : 'hover-shadow'"
            >

              <!-- STATUS BADGE -->
              <span
                class="position-absolute top-0 end-0 m-3 badge rounded-pill"
                :class="promo.used ? 'bg-secondary' : 'bg-success'"
              >
                {{ promo.used ? 'Used' : 'Active' }}
              </span>

              <!-- TITLE -->
              <h6 class="fw-bold mb-2">
                {{ promo.title }}
              </h6>

              <!-- DESCRIPTION -->
              <p class="small mb-3">
                {{ promo.description }}
              </p>

              <!-- PROMO CODE -->
              <div
                class="d-flex align-items-center gap-2 mb-3"
              >
                <span
                  class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"
                >
                  {{ promo.code }}
                </span>

                <button
                  class="btn btn-sm btn-outline-primary rounded-pill"
                  :disabled="promo.used"
                  @click="copyPromoCode(promo.code)"
                >
                  Copy
                </button>
              </div>

              <!-- FOOTER -->
              <div class="d-flex justify-content-between align-items-center small text-muted">
                <span>
                  ⏰ Expires {{ promo.expiry }}
                </span>
                <span>
                  🎁 {{ promo.type }}
                </span>
              </div>

            </div>
          </div>

        </div>

        <!-- EMPTY STATE -->
        <div
          v-if="promoVouchers.length === 0"
          class="text-center py-5 text-muted"
        >
          <h6 class="fw-bold mb-2">No active promos</h6>
          <p>Check back later for exciting offers</p>
        </div>

      </div>

      <!-- PLACEHOLDER FOR OTHER TABS -->
      <div v-else class="text-center py-5">
        <h5 class="fw-bold mb-2">Coming Soon</h5>
        <p class="text-muted">
          This section will be available in a future update.
        </p>
      </div>


      <!-- ADD PAYMENT MODAL -->
      <div
        v-if="showAddPayment"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.4); z-index: 1050;"
      >
        <div class="bg-white rounded-5 p-4 p-md-5 shadow" style="width: 100%; max-width: 480px;">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Add Payment Method</h5>
            <button class="btn btn-sm btn-light rounded-circle" @click="showAddPayment = false">
              ✕
            </button>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Card Type</label>
            <select v-model="newPayment.type" class="form-select rounded-4">
              <option>Visa</option>
              <option>Mastercard</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Cardholder Name</label>
            <input
              type="text"
              class="form-control rounded-4"
              placeholder="John Doe"
              v-model="newPayment.name"
            />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Card Number</label>
            <input
              type="text"
              class="form-control rounded-4"
              placeholder="•••• •••• •••• ••••"
              maxlength="16"
              v-model="newPayment.cardNumber"
            />
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Expiry Date</label>
            <input
              type="text"
              class="form-control rounded-4"
              placeholder="MM/YY"
              v-model="newPayment.expiry"
            />
          </div>

          <div class="d-flex gap-2">
            <button
              class="btn btn-light rounded-pill w-50"
              @click="showAddPayment = false"
            >
              Cancel
            </button>
            <button
              class="btn btn-primary rounded-pill w-50"
              @click="addPaymentMethod"
            >
              Add Card
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- LOGOUT -->
    <button type="button" style="margin-top: 3%;" class="btn btn-danger bg-opacity-10 text-danger w-100 py-3 fw-bold rounded-4 logout-btn text-white" @click="logout">
      Log Out Account
    </button>

  </div>
</template>

