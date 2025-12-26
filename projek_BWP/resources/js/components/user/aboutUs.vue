<template>
  <section class="stats-section">
    <div class="about-container">

      <!-- TEXT -->
      <span class="about-eyebrow">About Tixly</span>

      <h1 class="about-title">
        We make event<br />
        ticketing enjoyable &<br />
        <span class="highlight">effortless</span> for leading<br />
        culture houses<span class="dot">.</span>
      </h1>

      <!-- IMAGE -->
      <div class="image-wrapper">
      <img
        src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&q=80"
        alt="Tixly platform preview"
      />
      </div>
    </div>

    <div class="stats-container">
      <h2 class="stats-title">
        Dedicated to performing<br />
        arts venues and culture<br />
        houses
      </h2>
      <p class="stats-description">
        Millions of event tickets are sold each month through Tixly for some
        of the most prestigious performing arts organisations and multi-event
        venues across the Nordics, UK, Europe and North America.
      </p>

      <div class="stats-grid">
        <div class="stat">
          <div class="stat-number">{{ stats.totalProducts }}</div>
          <div class="stat-label">Total products</div>
        </div>

        <div class="stat">
          <div class="stat-number">{{ stats.totalVenues }}</div>
          <div class="stat-label">Selling tickets in {{ stats.totalVenues }} Venues</div>
        </div>

        <div class="stat">
          <div class="stat-number">{{ stats.totalOrders }}</div>
          <div class="stat-label">Tickets sold annually</div>
        </div>

        <div class="stat">
          <div class="stat-number">{{ stats.totalUsers }}</div>
          <div class="stat-label">Total members</div>
        </div>
      </div>
    </div>

    <!-- WHY TIXLY SECTION -->
    <section class="why-section">
      <span class="about-eyebrow">Why you should choose Tixly?</span>

      <h1 class="about-title why-title">
        One of the fastest<br />
        growing event<br />
        ticketing solutions<span class="dot">.</span>
      </h1>

      <div class="content-grid">
        <!-- IMAGE -->
        <div class="image-wrapper why-image">
          <div class="decorative-circle"></div>
          <img
            src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80"
            alt="Tixly platform preview showing seating chart and analytics"
          />
        </div>

        <!-- TEXT -->
        <div class="text-content">
          <p class="content-paragraph">
            Tixly is one of the fastest growing event ticketing software 
            companies today. Millions of tickets are sold each month 
            through our ticketing software for some of the most 
            prestigious performing arts organisations and multi-event 
            culture venues across Europe, North America and Australia.
          </p>

          <p class="content-paragraph">
            Over the last 11 years, Tixly has revolutionised the Nordic and 
            European live event ticketing scene, offering high quality 
            solutions and features, presented in the most user-friendly 
            way, to help ensure frictionless event experiences for 
            organisations and the ticket buyers they serve.
          </p>
        </div>
      </div>
    </section>

  </section>
</template>

<script>
export default {
  name: "AboutUs",
  data() {
    return {
      stats: {
        totalProducts: 0,
        totalVenues: 0,
        totalOrders: 0,
        totalUsers: 0
      }
    }
  },
  mounted() {
    fetch('/api/stats')
      .then(res => res.json())
      .then(data => {
        this.stats = data
      })
  }
}
</script>

<style scoped>
.about-hero {
  padding: 120px 24px;
  background: #fff;
}

.about-container {
  max-width: 1000px;
  margin: 0 auto;
  text-align: center;
}

/* Eyebrow */
.about-eyebrow {
  display: inline-block;
  font-size: 1.1rem;
  font-weight: 600;
  color: #000;
  margin-bottom: 20px;
}

/* Title */
.about-title {
  font-size: clamp(2.8rem, 5vw, 4.8rem);
  font-weight: 800;
  line-height: 1.1;
  color: #000;
}

/* Highlight */
.highlight {
  color: #ff4d1c;
}

.dot {
  color: #ff4d1c;
}

/* IMAGE CONTAINER */
.image-wrapper {
  position: relative;
  max-width: 100%;
  border-radius: 28px;
  overflow: hidden;
  margin-top: 5%;
  margin-bottom: 5%;

  /* smooth visual edge */
  box-shadow:
    0 30px 60px rgba(0, 0, 0, 0.12),
    inset 0 0 0 1px rgba(255,255,255,0.4);
}

.image-wrapper img {
  width: 100%;
  height: 600px;
  display: block;
  object-fit: cover;
}

/* Statistic */
.stats-section {
  padding: 140px 24px;
  background: #fff;
}

.stats-container {
  max-width: 1100px;
  margin: 0 auto;
  text-align: center;
}

.stats-title {
  font-size: clamp(2.6rem, 5vw, 3.8rem);
  font-weight: 800;
  line-height: 1.15;
  margin-bottom: 30px;
}

.stats-description {
  max-width: 720px;
  margin: 0 auto 90px;
  font-size: 1.05rem;
  line-height: 1.7;
  color: #444;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 40px;
}

.stat-number {
  font-size: 3.5rem;
  font-weight: 800;
  color: #000;
}

.stat-label {
  margin-top: 12px;
  font-size: 0.95rem;
  font-weight: 600;
  color: #111;
}

/* WHY SECTION */
.why-section {
  max-width: 1200px;
  margin: 160px auto 0;
  padding: 0 24px;
}

/* Title tweak */
.why-title {
  max-width: 700px;
  margin: 0 auto 80px;
  text-align: center;
}

/* CONTENT GRID */
.content-grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 80px;
  align-items: center;
}

/* IMAGE STYLE */
.why-image {
  position: relative;
}

/* Decorative accent */
.decorative-circle {
  position: absolute;
  top: -40px;
  left: -40px;
  width: 160px;
  height: 160px;
  border-radius: 50%;
  background: rgba(255, 77, 28, 0.08);
  z-index: 0;
}

.why-image img {
  position: relative;
  z-index: 1;
}

/* TEXT CONTENT */
.text-content {
  max-width: 520px;
}

.content-paragraph {
  font-size: 1.05rem;
  line-height: 1.8;
  color: #444;
  margin-bottom: 28px;
}

/* Responsive */
@media (max-width: 900px) {
  .content-grid {
    grid-template-columns: 1fr;
    gap: 60px;
  }

  .text-content {
    max-width: 100%;
    text-align: center;
  }

  .decorative-circle {
    display: none;
  }
}

/* Responsive */
@media (max-width: 600px) {
  .about-hero {
    padding: 90px 20px;
  }

  .about-title {
    margin-bottom: 40px;
  }

  .image-wrapper {
    border-radius: 20px;
  }
}
</style>
