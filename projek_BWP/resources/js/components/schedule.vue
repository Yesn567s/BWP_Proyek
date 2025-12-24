<script setup>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const movie = ref(null)
const schedules = ref([])
const selectedDate = ref(null)
const loading = ref(true)
const error = ref('')

const numberFormatter = new Intl.NumberFormat('id-ID')

const loadSchedule = async () => {
	loading.value = true
	error.value = ''

	try {
		const productId = route.params.id || route.query.productId || 1
		const { data } = await axios.get(`/api/movies/${productId}/schedule`)

		movie.value = data.movie
		schedules.value = data.schedules
		selectedDate.value = data.schedules?.[0]?.date ?? null
	} catch (err) {
		error.value = 'Unable to load schedule data right now.'
	} finally {
		loading.value = false
	}
}

onMounted(loadSchedule)

const dates = computed(() => {
	const uniqueDates = [...new Set(schedules.value.map(s => s.date))]

	return uniqueDates.map(dateStr => {
		const date = new Date(dateStr)

		return {
			value: dateStr,
			weekday: date.toLocaleDateString('en-US', { weekday: 'short' }),
			day: date.toLocaleDateString('en-US', { day: 'numeric' }),
			month: date.toLocaleDateString('en-US', { month: 'short' }),
		}
	})
})

const venuesForSelectedDate = computed(() => {
	if (!selectedDate.value) return []

	const filtered = schedules.value.filter(s => s.date === selectedDate.value)
	const grouped = {}

	filtered.forEach(slot => {
		const key = slot.venue_id ?? `studio-${slot.studio_name ?? slot.id}`

		if (!grouped[key]) {
			grouped[key] = {
				venueName: slot.venue_name ?? 'Venue TBA',
				location: slot.location ?? 'Location TBA',
				studio: slot.studio_name ?? 'Studio',
				slots: [],
			}
		}

		grouped[key].slots.push({
			id: slot.id,
			start: slot.start_time,
			end: slot.end_time,
		})
	})

	return Object.values(grouped)
})

const formatPrice = amount => numberFormatter.format(amount ?? 0)
</script>

<template>
	<div class="container py-5">
		<div class="mb-4">
			<h1 class="fw-bold">Choose Date &amp; Venue</h1>
			<p class="text-muted mb-0">Pick the day and place to watch your movie</p>
		</div>

		<div v-if="loading" class="text-center py-5">
			<div class="spinner-border text-primary"></div>
			<p class="mt-3 text-muted">Loading schedule...</p>
		</div>

		<div v-else-if="error" class="alert alert-danger" role="alert">
			{{ error }}
		</div>

		<div v-else-if="movie" class="d-flex flex-column gap-4">
			<!-- Movie info -->
			<div class="card border-0 shadow-sm rounded-4">
				<div class="card-body p-4">
					<div class="row g-4 align-items-center">
						<div class="col-md-4">
							<div class="poster-frame rounded-4 overflow-hidden shadow-sm">
								<img
									:src="movie.poster"
									alt="Poster"
									class="w-100 h-100"
								/>
							</div>
						</div>
						<div class="col-md">
							<span class="badge bg-primary-soft text-primary fw-semibold mb-2">
								{{ movie.category || 'Movie' }}
							</span>
							<h2 class="fw-bold mb-2">{{ movie.title }}</h2>
							<p class="text-muted mb-3">{{ movie.desc }}</p>
							<div class="d-flex align-items-center gap-3">
								<div class="fw-bold text-primary fs-4">Rp {{ formatPrice(movie.price) }}</div>
								<small class="text-muted">Base price</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Date selection -->
			<div>
				<div class="d-flex justify-content-between align-items-center mb-3">
					<div>
						<h5 class="fw-bold mb-0">Choose a Date</h5>
						<small class="text-muted">Select the day you want</small>
					</div>
					<small class="text-muted">{{ dates.length }} dates available</small>
				</div>

				<div class="d-flex gap-3 overflow-auto pb-2">
					<button
						v-for="date in dates"
						:key="date.value"
						class="date-card btn p-3 border shadow-sm rounded-4 text-start flex-shrink-0"
						:class="{ 'date-card-active': selectedDate === date.value }"
						@click="selectedDate = date.value"
					>
						<div class="text-uppercase small text-muted mb-1">{{ date.weekday }}</div>
						<div class="fw-bold fs-5">{{ date.day }}</div>
						<div class="text-muted small">{{ date.month }}</div>
					</button>
				</div>
			</div>

			<!-- Venue selection -->
			<div>
				<div class="d-flex justify-content-between align-items-center mb-3">
					<div>
						<h5 class="fw-bold mb-0">Select Venue</h5>
						<small class="text-muted">Showtimes for the chosen date</small>
					</div>
				</div>

				<div class="card border-0 shadow-sm rounded-4 venue-list">
					<div class="card-body p-0">
						<div v-if="venuesForSelectedDate.length === 0" class="p-4 text-center text-muted">
							No venues available for this date.
						</div>

						<div
							v-for="venue in venuesForSelectedDate"
							:key="venue.venueName + venue.location"
							class="border-bottom p-4"
						>
							<div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
								<div>
									<h6 class="fw-bold mb-1">{{ venue.venueName }}</h6>
									<small class="text-muted">{{ venue.location }}</small>
								</div>
								<span class="badge bg-light text-secondary">{{ venue.studio }}</span>
							</div>

							<div class="d-flex flex-wrap gap-2 mt-3">
								<button
									v-for="slot in venue.slots"
									:key="slot.id"
									class="btn btn-outline-primary rounded-pill px-3 py-2"
								>
									{{ slot.start }} - {{ slot.end }}
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
.poster-frame {
	height: 320px;
	background: #f8f9fa;
}

.poster-frame img {
	object-fit: cover;
}

.bg-primary-soft {
	background: rgba(13, 110, 253, 0.1);
}

.date-card {
	width: 130px;
	background: #fff;
	border-color: #e9ecef;
	transition: all 0.2s ease;
}

.date-card-active {
	border-color: var(--bs-primary);
	background: #eef4ff;
	box-shadow: 0 10px 25px rgba(13, 110, 253, 0.12);
}

.venue-list {
	max-height: 440px;
	overflow-y: auto;
}
</style>