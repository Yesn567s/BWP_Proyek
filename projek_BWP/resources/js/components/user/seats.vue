<script setup>
import axios from 'axios'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const props = defineProps({
	scheduleId: { type: Number, default: null },
	studioName: { type: String, default: null },
	venueName: { type: String, default: null },
	date: { type: String, default: null },
})

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const error = ref('')
const seatRows = ref({})
const schedule = ref(null)
const selectedSeats = ref([])

const resolvedScheduleId = computed(() => {
	return (
		props.scheduleId ??
		Number(route.params.scheduleId || route.query.scheduleId || NaN)
	)
})

const goBack = () => router.back()

const fetchSeats = async () => {
	const id = resolvedScheduleId.value
	if (!id) {
		error.value = 'Schedule not found.'
		loading.value = false
		return
	}

	loading.value = true
	error.value = ''

	try {
		const { data } = await axios.get(`/api/schedules/${id}/seats`)
		schedule.value = data.schedule
		seatRows.value = data.rows || {}
		pruneSelectedSeats()
		if (schedule.value.category_id > 1 && schedule.value.category_id != 7) {
			localStorage.clear();
			const data = {
			'scheduleId': resolvedScheduleId.value,
			'id': schedule.value.product_id,
			'name': schedule.value.name,
			'totalPrice': schedule.value.price*1,
		};

		console.log('Checkout data:', data);
		localStorage.setItem('cart', JSON.stringify(data));
		router.push({ name: 'checkout' });	
		}
	} catch (err) {
		console.error(err)
		error.value = 'Unable to load seat availability right now.'
	} finally {
		loading.value = false
	}
}

onMounted(fetchSeats)

watch(resolvedScheduleId, (newId, oldId) => {
	if (newId && newId !== oldId) {
		fetchSeats()
	}
})

const sortedRows = computed(() => {
	return Object.entries(seatRows.value)
		.sort(([rowA], [rowB]) => rowA.localeCompare(rowB))
		.map(([row, seats]) => ({ row, seats }))
})

const segmentedRows = computed(() => {
	return sortedRows.value.map(({ row, seats }) => {
		const sortedSeats = [...seats].sort((a, b) => a.number - b.number)
		const segments = {
			left: [],
			center: [],
			right: [],
		}

		sortedSeats.forEach(seat => {
			if (seat.number <= 2) {
				segments.left.push(seat)
			} else if (seat.number >= 3 && seat.number <= 6) {
				segments.center.push(seat)
			} else {
				segments.right.push(seat)
			}
		})

		return { row, ...segments }
	})
})

const buildRowSlots = rowData => {
	const slots = []
	const pushSeat = seat => slots.push({ type: 'seat', seat })
	const pushSpacer = suffix =>
		slots.push({ type: 'spacer', id: `${rowData.row}-spacer-${suffix}` })

	rowData.left.forEach(pushSeat)
	pushSpacer('left')
	rowData.center.forEach(pushSeat)
	pushSpacer('right')
	rowData.right.forEach(pushSeat)

	return slots
}

const rowLayouts = computed(() =>
	segmentedRows.value.map(row => ({
		...row,
		slots: buildRowSlots(row),
	}))
)

const seatLabel = (row, seatNumber) => `${row}${seatNumber}`

const seatClass = seat => ({
	'seat-button': true,
	'seat-reserved': seat.status === 'reserved',
	'seat-maintenance': seat.status === 'maintenance',
	'seat-selected': isSeatSelected(seat.id),
})

const isSeatSelected = seatId =>
	selectedSeats.value.some(selectedSeat => selectedSeat.id === seatId)

const toggleSeatSelection = (rowLabel, seat) => {
	if (seat.status !== 'available') return

	if (isSeatSelected(seat.id)) {
		selectedSeats.value = selectedSeats.value.filter(sel => sel.id !== seat.id)
		return
	}

	selectedSeats.value = [
		...selectedSeats.value,
		{ id: seat.id, row: rowLabel, number: seat.number },
	]
}

const pruneSelectedSeats = () => {
	const availableSeatIds = new Set()
	Object.values(seatRows.value).forEach(rowSeats => {
		rowSeats.forEach(seat => {
			if (seat.status === 'available') {
				availableSeatIds.add(seat.id)
			}
		})
	})

	selectedSeats.value = selectedSeats.value.filter(sel =>
		availableSeatIds.has(sel.id)
	)
}

const headerInfo = computed(() => ({
	studio: schedule.value?.studio_name ?? props.studioName ?? 'Studio',
	venue: schedule.value?.venue_name ?? props.venueName ?? 'Venue',
	location: schedule.value?.location ?? '',
	date: schedule.value?.date ?? props.date ?? '',
	time: schedule.value ? `${schedule.value.start_time} - ${schedule.value.end_time}` : '',
	movie: schedule.value?.movie_title ?? '',
}))


const totalPrice = computed(() => {
	if (!schedule.value || selectedSeats.value.length === 0) return 0
	console.log(schedule.value)

	return schedule.value.price * selectedSeats.value.length
})

const formatPrice = (price) => {
	return new Intl.NumberFormat('id-ID', {
		style: 'currency',
		currency: 'IDR',
		minimumFractionDigits: 0,
		maximumFractionDigits: 0,
	}).format(price)
}

const handleCheckout = async () => {
	if (selectedSeats.value.length === 0) {
		alert('Please select at least one seat')
		return
	}
	
	try {
		localStorage.clear();
		// Send checkout data to backend
		const data = {
			'scheduleId': resolvedScheduleId.value,
			'seats': selectedSeats.value,
			'totalPrice': totalPrice.value,
		};

		console.log('Checkout data:', data);
		localStorage.setItem('cart', JSON.stringify(data));
		// const { data } = await axios.post('/api/checkout', {
		// 	scheduleId: resolvedScheduleId.value,
		// 	seats: selectedSeats.value,
		// 	totalPrice: totalPrice.value,
		// })
		
		// Navigate to payment page or success page
		router.push({ name: 'checkout' });	
	} catch (err) {
		console.error(err)
		alert('Checkout failed. Please try again.')
	}
}
</script>

<template>
	<div class="seat-page container py-5">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
			<div>
				<p class="text-uppercase text-muted small mb-1">{{ headerInfo.venue }} • {{ headerInfo.studio }}</p>
				<h1 class="fw-bold mb-1">Select Seats</h1>
				<p class="text-muted mb-0">
					{{ headerInfo.movie }}
					<span v-if="headerInfo.movie && headerInfo.time">•</span>
					{{ headerInfo.time }}
				</p>
				<p class="text-muted small">{{ headerInfo.date }} {{ headerInfo.location ? `• ${headerInfo.location}` : '' }}</p>
			</div>
			<button class="btn btn-outline-primary fw-semibold" @click="goBack">&larr; Back to schedule</button>
		</div>

		<div v-if="loading" class="text-center py-5">
			<div class="spinner-border text-primary"></div>
			<p class="mt-3 text-muted">Fetching seats...</p>
		</div>

		<div v-else-if="error" class="alert alert-danger" role="alert">
			{{ error }}
		</div>

		<div v-else class="seat-layout card border-0 shadow-sm rounded-4 p-4">
			<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
				<div class="d-flex align-items-center gap-3">
					<span class="legend legend-available">Available</span>
					<span class="legend legend-reserved">Reserved</span>
					<span class="legend legend-maintenance">Maintenance</span>
				</div>
				<small class="text-muted">Select the seats you want to buy</small>
			</div>


			<div class="seat-grid mt-4">
				<div
					v-for="row in rowLayouts"
					:key="row.row"
					class="seat-row"
				>
					<div class="row-label">{{ row.row }}</div>
					<div class="row-grid">
						<template
							v-for="(slot, index) in row.slots"
							:key="slot.type === 'seat' ? slot.seat.id : slot.id"
						>
							<div v-if="slot.type === 'spacer'" class="seat-spacer"></div>
							<button
								v-else
								:type="button"
								class="btn"
								:class="seatClass(slot.seat)"
								:disabled="slot.seat.status !== 'available'"
								@click="toggleSeatSelection(row.row, slot.seat)"
							>
								{{ seatLabel(row.row, slot.seat.number) }}
							</button>
						</template>
					</div>
				</div>
			</div>
            <br>
            <br>
            <div class="screen text-center text-uppercase fw-semibold">Cinema Screen</div>
		</div>
		<div class="seat-footer container border-0 shadow-sm rounded-4 p-4 mt-4 bg-white d-sm-inline-flex">
			<div class="card-body border-0 p-3 rounded-4 shadow-lg w-75 h-25">
				<h4>Selected Seats</h4>
				<div class="selected-seat-list mt-3">
					<template v-if="selectedSeats.length">
						<button
							v-for="seat in selectedSeats"
							:key="seat.id"
							class="btn seat-button seat-button--summary"
							type="button"
						>
							{{ seatLabel(seat.row, seat.number) }}
						</button>
					</template>
					<p v-else class="text-muted mb-0">No seats selected yet.</p>
				</div>
			</div>
			<div class="card-body border-0 p-3 rounded-4 shadow-lg w-25 h-25">
				<div class="d-flex flex-column justify-content-between h-100">
					<div>
						<h4>Total Price</h4>
						<h3 class="px-3 text-primary fw-bold">{{ formatPrice(totalPrice) }}</h3>
						<p class="text-muted small px-3">{{ selectedSeats.length }} seat(s)</p>
					</div>
					<button 
						class="btn btn-primary btn-lg fw-semibold w-100 mt-3" 
						@click="handleCheckout"
						:disabled="selectedSeats.length === 0"
					>
						Checkout
					</button>
				</div>
			</div>



		</div>
	</div>
</template>

<style scoped>
:global(body) {
	background: #f5f6fb;
}

.seat-page {
	min-height: 100vh;
}

.screen {
	background: linear-gradient(90deg, #dfe7ff, #f7f9ff);
	border-radius: 16px;
	padding: 14px;
	letter-spacing: 0.2em;
	color: #4c5dff;
}

.seat-grid {
	display: flex;
	flex-direction: column;
	gap: 12px;
}


.seat-row {
	display: grid;
	grid-template-columns: 40px 1fr;
	align-items: center;
	gap: 16px;
	padding: 4px 0;
}

.row-label {
	font-weight: 600;
	color: #94a3b8;
	text-align: center;
}

.row-grid {
	display: grid;
	grid-template-columns: repeat(10,100px);
	justify-content: center;
	justify-items: center;
	gap: 16px;
	width: 100%;
}

.seat-spacer {
	width: 30px;
	height: 44px;
	visibility: hidden;
}

.seat-button {
	background: #0d1b4c;
	border-radius: 12px;
	color: #fff;
	font-weight: 600;
	border: none;
	padding: 10px 0;
	width: 110px;
	transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.seat-selected:not(.seat-reserved):not(.seat-maintenance) {
	background: #f59e0b;
	color: #1f2937;
	box-shadow: 0 10px 25px rgba(245, 158, 11, 0.35);
}

.seat-button:not(:disabled):hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
}

.seat-button:disabled {
	cursor: not-allowed;
}

.seat-reserved {
	background: #cbd5f5;
	color: #5a6eaa;
}

.seat-maintenance {
	background: #ffe4d6;
	color: #c2410c;
}

.legend {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	font-size: 0.9rem;
	font-weight: 500;
}

.legend::before {
	content: '';
	width: 18px;
	height: 18px;
	border-radius: 6px;
}

.legend-available::before {
	background: #0d1b4c;
}

.legend-reserved::before {
	background: #cbd5f5;
}

.legend-maintenance::before {
	background: #ffe4d6;
}

.selected-seat-list {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.seat-button--summary {
	padding: 8px 16px;
	min-width: 0;
	border-radius: 999px;
	width: auto;
}
</style>
