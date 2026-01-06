
<template>
	<section class="admin-section">
		<header class="section-header d-flex flex-wrap gap-4 justify-content-between align-items-start mb-4">
			<div>
				<p class="eyebrow">Experiences</p>
				<h1 class="mb-1">Tix Fun Catalog</h1>
				<p class="text-muted mb-0">Showing {{ filteredTickets.length }} items</p>
			</div>

			<div class="d-flex flex-column gap-3 align-items-end">
				<div class="d-flex align-items-center gap-3">
					<div class="search-wrapper">
						<i class="bi bi-search search-icon"></i>
						<input
							v-model="search"
							type="text"
							class="search-input"
							placeholder="Search attractions, parks, events..."
							aria-label="Search fun tickets"
						/>
					</div>
					<button class="btn admin-pill-btn solid text-white text-center" style="background:linear-gradient(135deg,blue,blueviolet); border-radius: 35px; border-color: transparent;" @click="$router.push({ name: 'adminAddTixFun' })">
						<i class="bi bi-plus-lg me-2"></i>Add New TixFun
					</button>
				</div>
				

				<div class="filter-chips">
					<button
						class="filter-chip"
						:class="{ active: activeCategory === null }"
						@click="setCategory(null)"
					>
						<i class="bi bi-stars me-1"></i>
						All Fun
					</button>

					<button
						v-for="cat in funCategories"
						:key="cat.category_id"
						class="filter-chip"
						:class="{ active: activeCategory === cat.category_id }"
						@click="setCategory(cat.category_id)"
					>
						<img v-if="cat.icons" :src="`/${cat.icons}`" alt="" class="chip-icon" />
						{{ cat.category_name }}
					</button>
				</div>
			</div>
		</header>

		<div class="row g-4 mt-2">
			<div v-for="item in filteredTickets" :key="item.id" class="col-md-6 col-xl-4">
				<div class="panel-card catalog-card h-100 d-flex flex-column">
					<div class="rounded-4 overflow-hidden mb-3" style="height: 180px;">
						<img :src="item.imageUrl || '/images/fun/default.jpg'" class="w-100 h-100 object-fit-cover" :alt="item.title" />
					</div>
                    
					<div class="d-flex justify-content-between align-items-start mb-2">
						<div>
							<p class="text-primary fw-semibold small mb-1 text-uppercase">{{ item.category || 'Fun' }}</p>
							<h5 class="fw-bold mb-1">{{ item.title }}</h5>
							<p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ item.location || '—' }}</p>
						</div>

						<span class="badge bg-dark-subtle text-dark fw-semibold">ID #{{ item.id }}</span>
					</div>

					<div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
						<div>
							<small class="text-muted text-uppercase fw-semibold">Price from</small>
							<div class="fs-5 fw-bold text-primary">{{ formatPrice(item.price) }}</div>
						</div>
						<button class="admin-pill-btn solid btn btn-primary" @click="goManage(item.id)">View Details</button>
					</div>
				</div>
			</div>

			<div v-if="!filteredTickets.length && !loading" class="col-12 text-center text-muted py-5">
				No fun tickets found.
			</div>

			<div v-if="loading" class="col-12 text-center text-muted py-5">
				Loading catalog...
			</div>
		</div>
	</section>
</template>

<script setup>
	import { computed, onMounted, ref } from 'vue'
	import { useRouter } from 'vue-router'
	import axios from 'axios'

	const tickets = ref([])
	const categories = ref([])
	const search = ref('')
	const activeCategory = ref(null)
	const loading = ref(true)
	const router = useRouter()

    onMounted(async () => {
        try {
            const [catRes, ticketRes] = await Promise.all([
                axios.get('/api/categories'),
                axios.get('/api/tickets')
            ])

            categories.value = catRes.data || []
            tickets.value = ticketRes.data || []
        } catch (err) {
            console.error('Failed to load fun catalog', err)
            categories.value = []
            tickets.value = []
        } finally {
            loading.value = false
        }
    })

    const funCategories = computed(() => categories.value.filter(cat => {
        const name = (cat.category_name || '').toLowerCase()
        return !name.includes('movie') && !name.includes('food') && !name.includes('beverage')
    }))

    const funTickets = computed(() => tickets.value.filter(t => {
        const catName = (t.category || '').toLowerCase()
        return !catName.includes('movie') && !catName.includes('food') && !catName.includes('beverage')
    }))

    const filteredTickets = computed(() => {
        let data = funTickets.value

        if (activeCategory.value !== null) {
            data = data.filter(t => t.category_id === activeCategory.value)
        }

        if (search.value) {
            const term = search.value.toLowerCase()
            data = data.filter(t =>
                t.title?.toLowerCase().includes(term) ||
                t.location?.toLowerCase().includes(term)
            )
        }

        return data
    })

    const setCategory = (id) => {
        activeCategory.value = id
    }

	const goManage = (id) => {
		router.push({ name: 'adminTixFunDetail', params: { id } })
	}

    const formatPrice = (price) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(price) || 0)
</script>

<style scoped>

.search-wrapper {
	position: relative;
	width: 320px;
}

.search-icon {
	position: absolute;
	left: 16px;
	top: 50%;
	transform: translateY(-50%);
	color: #9ca3af;
	font-size: 1rem;
	pointer-events: none;
}

.search-input {
	width: 100%;
	padding: 12px 16px 12px 44px;
	border: 2px solid #e5e7eb;
	border-radius: 50px;
	font-size: 0.95rem;
	background: #fff;
	transition: all 0.2s ease;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.search-input:focus {
	outline: none;
	border-color: var(--bs-primary, #6366f1);
	box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.search-input::placeholder {
	color: #9ca3af;
}

.filter-chips {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
	justify-content: flex-end;
}

.filter-chip {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 18px;
	border: 2px solid #e5e7eb;
	border-radius: 50px;
	background: #fff;
	font-size: 0.875rem;
	font-weight: 600;
	color: #4b5563;
	cursor: pointer;
	transition: all 0.2s ease;
	white-space: nowrap;
}

.filter-chip:hover {
	border-color: var(--bs-primary, #6366f1);
	color: var(--bs-primary, #6366f1);
	background: #f5f3ff;
}

.filter-chip.active {
	background: linear-gradient(135deg, var(--bs-primary, #6366f1), #8b5cf6);
	border-color: transparent;
	color: #fff;
	box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
}

.filter-chip.active:hover {
	background: linear-gradient(135deg, #4f46e5, #7c3aed);
	color: #fff;
}

.chip-icon {
	width: 18px;
	height: 18px;
	object-fit: contain;
	filter: grayscale(0.3);
}

.filter-chip.active .chip-icon {
	filter: brightness(0) invert(1);
}
</style>
