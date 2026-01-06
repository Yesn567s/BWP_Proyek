<template>
	<div class="container my-5 animate-fadeIn" style="max-width: 900px;">
		<div class="d-flex align-items-center gap-3 mb-4">
			<button class="btn btn-light rounded-circle p-3" @click="goBack">
				←
			</button>

			<div>
				<h2 class="fw-bold mb-0">Add New TixFun</h2>
				<small class="text-muted fw-semibold">
					Create a new experience ticket for the catalog
				</small>
			</div>
		</div>

		<form @submit.prevent="handleSubmit" class="card shadow-lg border-0 p-5">
			<div class="row g-5">
				<div class="col-md-7">
					<div class="mb-4">
						<label class="form-label fw-semibold text-uppercase small">Experience Name</label>
						<input
							v-model="ticket.name"
							type="text"
							class="form-control form-control-lg"
							placeholder="e.g. Taman Safari Adventure"
							required
						/>
					</div>

					<div class="mb-4">
						<label class="form-label fw-semibold text-uppercase small">Category</label>
						<select v-model="ticket.category_id" class="form-select form-select-lg" required>
							<option value="" disabled>Select a category</option>
							<option
								v-for="cat in funCategories"
								:key="cat.category_id"
								:value="cat.category_id"
							>
								{{ cat.category_name }}
							</option>
						</select>
					</div>

					<div class="mb-4">
						<label class="form-label fw-semibold text-uppercase small">Description</label>
						<textarea
							v-model="ticket.description"
							class="form-control form-control-lg"
							rows="4"
							placeholder="Describe this experience..."
							required
						></textarea>
					</div>

					<div class="row g-3 mb-4">
						<div class="col-md-6">
							<label class="form-label fw-semibold text-uppercase small">Base Price (IDR)</label>
							<input
								v-model.number="ticket.base_price"
								type="number"
								class="form-control form-control-lg"
								placeholder="50000"
								required
								step="1000"
								min="0"
							/>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold text-uppercase small">Duration (minutes)</label>
							<input
								v-model.number="ticket.duration_minutes"
								type="number"
								class="form-control form-control-lg"
								placeholder="120"
								min="0"
							/>
							<small class="text-muted">Leave empty if not applicable</small>
						</div>
					</div>

					<div class="row g-3 mb-4">
						<div class="col-md-6">
							<label class="form-label fw-semibold text-uppercase small">Age Rating</label>
							<select v-model="ticket.age_rating" class="form-select form-select-lg">
								<option value="">All Ages</option>
								<option value="SU">SU (All Ages)</option>
								<option value="13+">13+</option>
								<option value="17+">17+</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold text-uppercase small">Genre / Tags</label>
							<input
								v-model="ticket.genre"
								type="text"
								class="form-control form-control-lg"
								placeholder="e.g. Adventure, Family"
							/>
						</div>
					</div>

					<div class="mb-4">
						<div class="form-check form-switch">
							<input
								v-model="ticket.requires_schedule"
								class="form-check-input"
								type="checkbox"
								id="requiresSchedule"
							/>
							<label class="form-check-label fw-semibold" for="requiresSchedule">
								Requires Schedule
							</label>
						</div>
						<small class="text-muted">Enable if this ticket has specific time slots</small>
					</div>

					<div class="mb-4">
						<label class="form-label fw-semibold text-uppercase small">Cover Image</label>
						<input
							class="form-control"
							type="file"
							accept="image/*"
							@change="handleFileChange"
						/>
					</div>
				</div>

				<!-- Right Column - Preview -->
				<div class="col-md-5 d-flex flex-column align-items-center">
					<div class="border rounded-4 p-3 bg-light w-100" style="height: 220px;">
						<img
							v-if="imagePreview"
							:src="imagePreview"
							class="img-fluid h-100 w-100 object-fit-cover rounded-3"
							alt="Preview"
						/>
						<div
							v-else
							class="h-100 d-flex align-items-center justify-content-center text-muted small fw-semibold"
						>
							Image Preview
						</div>
					</div>

					<small class="text-muted mt-3 text-center">
						Recommended: 16:9 aspect ratio, min 800x450px
					</small>

					<!-- Quick Preview Card -->
					<div class="mt-4 w-100 p-4 border rounded-4 bg-white">
						<p class="text-primary fw-semibold small mb-1 text-uppercase">
							{{ selectedCategoryName || 'Category' }}
						</p>
						<h5 class="fw-bold mb-2">{{ ticket.name || 'Experience Name' }}</h5>
						<p class="text-muted small mb-3">
							{{ ticket.description?.slice(0, 80) || 'Description preview...' }}{{ ticket.description?.length > 80 ? '...' : '' }}
						</p>
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<small class="text-muted text-uppercase fw-semibold">Price from</small>
								<div class="fs-5 fw-bold text-primary">{{ formatPrice(ticket.base_price) }}</div>
							</div>
							<span v-if="ticket.age_rating" class="badge bg-dark-subtle text-dark">
								{{ ticket.age_rating }}
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
				<button type="button" class="btn btn-link text-secondary fw-bold" @click="goBack">
					DISCARD
				</button>

				<button type="submit" class="btn btn-primary px-5 fw-bold" :disabled="submitting">
					{{ submitting ? 'SAVING...' : 'PUBLISH TIXFUN' }}
				</button>
			</div>
		</form>
	</div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const ticket = reactive({
	name: '',
	category_id: '',
	description: '',
	base_price: 50000,
	duration_minutes: null,
	age_rating: '',
	genre: '',
	requires_schedule: false
})

const categories = ref([])
const imageFile = ref(null)
const imagePreview = ref(null)
const submitting = ref(false)

onMounted(async () => {
	try {
		const res = await axios.get('/api/categories')
		categories.value = res.data || []
	} catch (err) {
		console.error('Failed to load categories', err)
	}
})

const funCategories = computed(() =>
	categories.value.filter(cat => {
		const name = (cat.category_name || '').toLowerCase()
		return !name.includes('movie') && !name.includes('food') && !name.includes('beverage')
	})
)

const selectedCategoryName = computed(() => {
	const cat = categories.value.find(c => c.category_id === ticket.category_id)
	return cat?.category_name || ''
})

const handleFileChange = (e) => {
	const file = e.target.files?.[0]
	if (!file) return

	imageFile.value = file
	imagePreview.value = URL.createObjectURL(file)
}

const formatPrice = (price) =>
	new Intl.NumberFormat('id-ID', {
		style: 'currency',
		currency: 'IDR',
		minimumFractionDigits: 0,
		maximumFractionDigits: 0
	}).format(Number(price) || 0)

const goBack = () => {
	router.push({ name: 'adminTixFunCatalog' })
}

const handleSubmit = async () => {
	submitting.value = true

	try {
		const formData = new FormData()
		formData.append('name', ticket.name)
		formData.append('category_id', ticket.category_id)
		formData.append('description', ticket.description)
		formData.append('base_price', ticket.base_price)
		formData.append('requires_schedule', ticket.requires_schedule ? 1 : 0)

		if (ticket.duration_minutes) {
			formData.append('duration_minutes', ticket.duration_minutes)
		}
		if (ticket.age_rating) {
			formData.append('age_rating', ticket.age_rating)
		}
		if (ticket.genre) {
			formData.append('genre', ticket.genre)
		}
		if (imageFile.value) {
			formData.append('image', imageFile.value)
		}

		await axios.post('/api/admin/tixfun', formData, {
			headers: { 'Content-Type': 'multipart/form-data' }
		})

		alert('TixFun created successfully!')
		router.push({ name: 'adminTixFunCatalog' })
	} catch (err) {
		console.error('Failed to create TixFun', err)
		alert('Failed to create TixFun. Please try again.')
	} finally {
		submitting.value = false
	}
}
</script>

<style scoped>
.animate-fadeIn {
	animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
	from {
		opacity: 0;
		transform: translateY(10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}
</style>
