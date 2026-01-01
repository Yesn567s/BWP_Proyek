<template>
  <div class="blog-container">
    <div class="container-fluid px-4 py-5">
      <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
          <div v-if="blogs.length === 0" class="text-center text-muted py-5">
            No blogs available.
          </div>
          <div v-else>
            <!-- Featured Blog -->
            <div v-if="blogs[0]" class="featured-blog mb-5">
              <div class="featured-image">
                <img :src="blogs[0].image || 'https://media.tenor.com/Ym6VeAcZoTcAAAAm/aaaah-cat.webp'" :alt="blogs[0].title" class="img-fluid rounded">
              </div>
              <div class="featured-content mt-3">
                <span class="category-badge">{{ blogs[0].category || 'BLOG' }}</span>
                <h2 class="fw-bold mt-2">{{ blogs[0].title }}</h2>
                <div class="meta-info text-muted small mt-2">
                  <span>by {{ blogs[0].author || 'Admin' }}</span>
                  <span class="ms-3">{{ formatDate(blogs[0].created_at) }}</span>
                </div>
                <p class="mt-3">{{ blogs[0].excerpt || blogs[0].content.substring(0, 150) + '...' }}</p>
              </div>
            </div>

            <!-- Grid Blogs -->
            <div class="row g-4">
              <div v-for="(blog, index) in blogs.slice(1)" :key="blog.id" class="col-md-6">
                <div class="blog-card">
                  <div class="blog-image">
                    <img :src="blog.image || 'https://via.placeholder.com/400x300'" :alt="blog.title" class="img-fluid rounded">
                  </div>
                  <div class="blog-card-content mt-3">
                    <span class="category-badge">{{ blog.category || 'BLOG' }}</span>
                    <h5 class="fw-bold mt-2">{{ blog.title }}</h5>
                    <div class="meta-info text-muted small mt-2">
                      <span>by {{ blog.author || 'Admin' }}</span>
                      <span class="ms-3">{{ formatDate(blog.created_at) }}</span>
                    </div>
                    <p class="mt-2 text-muted small">{{ blog.excerpt || blog.content.substring(0, 80) + '...' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="sidebar">
            <h4 class="fw-bold mb-4">MORE NEWS</h4>
            <div class="sidebar-divider mb-4"></div>
            <div v-if="blogs.length === 0" class="text-muted">
              No news available.
            </div>
            <div v-else class="news-list">
              <div v-for="blog in blogs.slice(0, 6)" :key="blog.id" class="news-item mb-4 pb-4">
                <h6 class="fw-bold">{{ blog.title }}</h6>
                <small class="text-muted">{{ formatDate(blog.created_at) }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const blogs = ref([]);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(async () => {
  try {
    const res = await axios.get('/api/blogs');
    // Sort by newest first
    blogs.value = res.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  } catch (error) {
    console.error('Error fetching blogs:', error);
  }
});
</script>

<style scoped>
.blog-container {
  background-color: #f8f9fa;
  min-height: 100vh;
}

.featured-blog {
  margin-bottom: 40px;
}

.featured-image {
  width: 100%;
  overflow: hidden;
  border-radius: 8px;
}

.featured-image img {
  width: 100%;
  height: 350px;
  object-fit: cover;
}

.category-badge {
  display: inline-block;
  color: #007bff;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.featured-content h2 {
  font-size: 28px;
  line-height: 1.3;
  margin-top: 15px;
}

.meta-info {
  font-size: 13px;
}

.blog-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.blog-card:hover {
  transform: translateY(-5px);
}

.blog-image {
  width: 100%;
  height: 220px;
  overflow: hidden;
  border-radius: 8px 8px 0 0;
}

.blog-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.blog-card-content {
  padding: 15px;
}

.blog-card-content h5 {
  font-size: 16px;
  line-height: 1.4;
}

.sidebar {
  position: sticky;
  top: 20px;
}

.sidebar h4 {
  font-size: 18px;
  padding-bottom: 15px;
}

.sidebar-divider {
  height: 2px;
  background: #333;
  width: 50px;
}

.news-item {
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: 15px;
}

.news-item h6 {
  font-size: 14px;
  line-height: 1.4;
  margin-bottom: 8px;
}

.news-item:last-child {
  border-bottom: none;
}
</style>