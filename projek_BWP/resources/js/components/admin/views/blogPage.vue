<template>
  <section class="blog-page">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-1">Blog Management</h2>
        <p class="text-muted mb-0">
          Manage articles, news, and promotional content.
        </p>
      </div>

      <router-link :to="{ name: 'adminBlogNew' }">
        <button class="btn btn-primary rounded-pill">
          + Write New Post
        </button>
      </router-link>
    </div>

    <!-- BLOG GRID -->
    <div v-if="loading" class="text-muted">Loading posts...</div>
        <div v-else class="row g-4">
        <div
            v-for="post in posts"
            :key="post.post_id"
            class="col-lg-6"
        >
            <div class="blog-card h-100 p-4" @click="$router.push({ name: 'adminBlogEdit', params: { id: post.post_id } })">
                <!-- img -->
                 <div class="image-wrapper">
                    <img :src="post.image" alt="cover" />

                    <span
                    class="status-badge"
                    :class="post.status"
                    >
                    {{ post.status }}
                    </span>

                    <span class="category-badge">
                    {{ post.category }}
                    </span>
                </div>
                <small class="text-muted">
                    {{ new Date(post.created_at).toLocaleDateString() }}
                </small>

                <h5 class="fw-bold mt-2">
                    {{ post.title }}
                </h5>

                <p class="text-muted">
                    {{ post.content.substring(0, 120) }}...
                </p>

                <div class="d-flex justify-content-between">
                    <button
                        class="btn btn-link p-0"
                        @click="managePost(post.post_id)"
                    >
                        Manage Post
                    </button>

                    <button
                        class="btn btn-link text-danger p-0"
                        @click="deletePost(post.post_id)"
                    >
                        Delete
                    </button>
                </div>

            </div>
        </div>
        </div>

  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const posts = ref([])
const loading = ref(true)

const fetchPosts = async () => {
  try {
    const res = await axios.get('/api/posts')
    posts.value = res.data
  } catch (err) {
    console.error('Failed to load posts', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchPosts)

</script>

<style scoped>
.blog-page {
  background: #f6f8fc;
  padding: 32px;
  min-height: 100vh;
}

/* CARD */
.blog-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
}

/* IMAGE */
.image-wrapper {
  position: relative;
  height: 200px;
}

.image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* BADGES */
.status-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  padding: 4px 10px;
  font-size: 0.7rem;
  border-radius: 999px;
  font-weight: 600;
}

.status-badge.published {
  background: #22c55e;
  color: white;
}

.status-badge.draft {
  background: #fbbf24;
  color: black;
}

.category-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(255, 255, 255, 0.9);
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 0.7rem;
  font-weight: 600;
}

/* BUTTONS */
.btn-link {
  font-weight: 600;
  text-decoration: none;
}
</style>
