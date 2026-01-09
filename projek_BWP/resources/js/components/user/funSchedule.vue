<script setup>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const selectedDate = ref(null)
const ticketCount = ref(1)
const loading = ref(true)
const error = ref('')

const numberFormatter = new Intl.NumberFormat('id-ID')

// Generate 7 dates starting from today
const dates = computed(() => {
	const result = []
	const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
	const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	
	for (let i = 0; i < 7; i++) {
		const date = new Date()
		date.setDate(date.getDate() + i)
		
		const year = date.getFullYear()
		const month = String(date.getMonth() + 1).padStart(2, '0')
		const day = String(date.getDate()).padStart(2, '0')
		
		result.push({
			value: `${year}-${month}-${day}`,
			weekday: weekdays[date.getDay()],
			day: date.getDate(),
			month: months[date.getMonth()]
		})
	}
	return result
})

const setSelectedDate = v => {
	selectedDate.value = v
}

const incrementTickets = () => {
	if (ticketCount.value < 10) ticketCount.value++
}

const decrementTickets = () => {
	if (ticketCount.value > 1) ticketCount.value--
}

const loadProduct = async () => {
	loading.value = true
	error.value = ''

	try {
		const productId = route.params.id || route.query.productId || 1
		const res = await axios.get(`/api/movies/${productId}/schedule`)
		product.value = res.data.movie
		
		if (dates.value.length > 0) {
			selectedDate.value = dates.value[0].value
		}
	} catch (err) {
		console.error(err)
		error.value = 'Unable to load product data right now.'
	} finally {
		loading.value = false
	}
}

onMounted(loadProduct)

const formatPrice = amount => numberFormatter.format(amount ?? 0)

const totalPrice = computed(() => {
	return (product.value?.price ?? 0) * ticketCount.value
})

const goToCheckout = () => {
	if (!selectedDate.value || !product.value) return

	const cartItem = {
		id: product.value.id,
		title: product.value.title,
		name: product.value.title,
		price: product.value.price,
		quantity: ticketCount.value,
		date: selectedDate.value,
		valid_until: selectedDate.value,
		type: 'fun'
	}

	router.push({
		name: 'checkout',
		query: {
			items: JSON.stringify([cartItem])
		}
	})
}
</script>

<template>
	<div class="container py-5">
		<div class="mb-4">
			<h1 class="fw-bold">Book Your Experience</h1>
			<p class="text-muted mb-0">Choose your date and number of tickets</p>
		</div>

		<div v-if="loading" class="text-center py-5">
			<div class="spinner-border text-primary"></div>
			<p class="mt-3 text-muted">Loading...</p>
		</div>

		<div v-else-if="error" class="alert alert-danger" role="alert">
			{{ error }}
		</div>

		<div v-else-if="product" class="d-flex flex-column gap-4">
			<!-- Product info -->
			<div class="card border-0 shadow-sm rounded-4">
				<div class="card-body p-4">
					<div class="row g-4 align-items-center">
						<div class="col-md-auto">
							<div class="poster-frame rounded-4 overflow-hidden shadow-sm">
								<img
									:src="product.poster"
									alt="Poster"
								/>
							</div>
						</div>
						<div class="col-md">
							<span class="badge bg-primary-soft text-primary fw-semibold mb-2">
								{{ product.category || 'Fun' }}
							</span>
							<h2 class="fw-bold mb-2">{{ product.title }}</h2>
							<p class="text-muted mb-3">{{ product.desc }}</p>
							<div class="d-flex align-items-center gap-3">
								<div class="fw-bold text-primary fs-4">Rp {{ formatPrice(product.price) }}</div>
								<small class="text-muted">per ticket</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Date selection -->
			<div class="card border-0 shadow-sm rounded-4">
				<div class="card-body p-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<h5 class="fw-bold mb-0">Choose a Date</h5>
							<small class="text-muted">Valid until selected date</small>
						</div>
					</div>

					<div class="d-flex gap-3 overflow-auto pb-2">
						<button 
							v-for="date in dates" 
							:key="date.value" 
							class="date-card btn p-3 border shadow-sm rounded-4 text-center flex-shrink-0" 
							:class="{ 'date-card-active': selectedDate === date.value }" 
							@click="setSelectedDate(date.value)"
						>
							<div class="text-uppercase small text-muted mb-1">{{ date.weekday }}</div>
							<div class="fw-bold fs-5">{{ date.day }}</div>
							<div class="text-muted small">{{ date.month }}</div>
						</button>
					</div>
				</div>
			</div>

			<!-- Ticket count -->
			<div class="card border-0 shadow-sm rounded-4">
				<div class="card-body p-4">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h5 class="fw-bold mb-0">Number of Tickets</h5>
							<small class="text-muted">Select how many tickets you need</small>
						</div>
						
						<div class="d-flex align-items-center gap-3">
							<button 
								class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
								style="width: 40px; height: 40px;"
								@click="decrementTickets"
								:disabled="ticketCount <= 1"
							>
								<i class="bi bi-dash fs-5"></i>
							</button>
							
							<span class="fs-4 fw-bold" style="min-width: 40px; text-align: center;">
								{{ ticketCount }}
							</span>
							
							<button 
								class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
								style="width: 40px; height: 40px;"
								@click="incrementTickets"
								:disabled="ticketCount >= 10"
							>
								<i class="bi bi-plus fs-5"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Summary & Checkout -->
			<div class="card border-0 shadow-sm rounded-4 bg-light">
				<div class="card-body p-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<span class="text-muted">{{ ticketCount }} ticket(s) × Rp {{ formatPrice(product.price) }}</span>
						<span class="text-muted">Valid until: {{ selectedDate }}</span>
					</div>
					
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<small class="text-muted">Total Price</small>
							<div class="fs-3 fw-bold text-primary">Rp {{ formatPrice(totalPrice) }}</div>
						</div>
						
						<button 
							class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"
							:disabled="!selectedDate"
							@click="goToCheckout"
						>
							Checkout
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
.poster-frame {
	background: #f8f9fa;
}

.poster-frame img {
	max-width: 200px;
	border-radius: 1rem;
}

.bg-primary-soft {
	background: rgba(13, 110, 253, 0.1);
}

.date-card {
	min-width: 90px;
	background: #fff;
	border-color: #e9ecef;
	transition: all 0.2s ease;
}

.date-card:hover {
	border-color: var(--bs-primary);
	background: #f8f9ff;
}

.date-card-active {
	border-color: var(--bs-primary) !important;
	background: #eef4ff !important;
	box-shadow: 0 10px 25px rgba(13, 110, 253, 0.12);
}
</style>
