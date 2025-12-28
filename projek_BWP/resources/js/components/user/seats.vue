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

const seatLabel = (row, seatNumber) => `${row}${seatNumber}`

const seatClass = seat => ({
	'seat-button': true,
	'seat-reserved': seat.status === 'reserved',
	'seat-maintenance': seat.status === 'maintenance',
})

const headerInfo = computed(() => ({
	studio: schedule.value?.studio_name ?? props.studioName ?? 'Studio',
	venue: schedule.value?.venue_name ?? props.venueName ?? 'Venue',
	location: schedule.value?.location ?? '',
	date: schedule.value?.date ?? props.date ?? '',
	time: schedule.value ? `${schedule.value.start_time} - ${schedule.value.end_time}` : '',
	movie: schedule.value?.movie_title ?? '',
}))
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
			<button class="btn btn-link fw-semibold text-primary" @click="goBack">&larr; Back to schedule</button>
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
				<small class="text-muted">Tap a seat to continue (coming soon)</small>
			</div>


			<div class="seat-grid mt-4">
				<div
					v-for="row in segmentedRows"
					:key="row.row"
					class="seat-row"
				>
					<div class="row-label">{{ row.row }}</div>
					<div class="row-sections">
						<div class="seat-section seat-section--edge">
							<button
								v-for="seat in row.left"
								:key="seat.id"
								:type="button"
								class="btn"
								:class="seatClass(seat)"
								:disabled="seat.status !== 'available'"
							>
								{{ seatLabel(row.row, seat.number) }}
							</button>
						</div>
						<div class="aisle"></div>
						<div class="seat-section seat-section--center">
							<button
								v-for="seat in row.center"
								:key="seat.id"
								:type="button"
								class="btn"
								:class="seatClass(seat)"
								:disabled="seat.status !== 'available'"
							>
								{{ seatLabel(row.row, seat.number) }}
							</button>
						</div>
						<div class="aisle"></div>
						<div class="seat-section seat-section--edge">
							<button
								v-for="seat in row.right"
								:key="seat.id"
								:type="button"
								class="btn"
								:class="seatClass(seat)"
								:disabled="seat.status !== 'available'"
							>
								{{ seatLabel(row.row, seat.number) }}
							</button>
						</div>
					</div>
				</div>
			</div>
            <br>
            <br>
            <div class="screen text-center text-uppercase fw-semibold">Cinema Screen</div>

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

.row-sections {
	display: grid;
	grid-template-columns: auto 24px auto 24px auto;
	gap: 16px;
	width: 100%;
	align-items: center;
}

.seat-section {
	display: grid;
	gap: 10px;
}

.seat-section--edge {
	grid-template-columns: repeat( 2,minmax(50px, 1fr));
}

.seat-section--center {
	grid-template-columns: repeat(4, minmax(50px, 1fr));
}

.aisle {
	width: 24px;
}

.seat-button {
	background: #0d1b4c;
	border-radius: 12px;
	color: #fff;
	font-weight: 600;
	border: none;
	padding: 10px 0;
	transition: transform 0.15s ease, box-shadow 0.15s ease;
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
</style>
