<style scoped>
.logo { font-size:26px; font-weight:800; }
.dot { color:#ff4d1c; font-size:26px; }

.menu-btn {
  background:none;
  border:none;
  font-weight:600;
  display:flex;
  gap:6px;
  align-items:center;
  cursor:pointer;
}

.bi-chevron-down {
  transition: transform .25s ease;
}
.rotate {
  transform: rotate(180deg);
}

.underline {
  position:absolute;
  bottom:-12px;
  left:0;
  width:80px;
  height:3px;
  background:#ff4d1c;
  border-radius:4px;
}

.mega-menu {
  position: absolute;
  top: 160%;
  left: 50%;

  width: 900px;
  max-width: calc(100vw - 80px);

  transform: translateX(-50%) translateY(-20px);

  background: #fff;
  border-radius: 24px;
  padding: 48px;
  box-shadow: 0 40px 80px rgba(0,0,0,.12);

  opacity: 0;
  pointer-events: none;
  transition: all .4s ease;
}

.mega-menu.smart-align {
  transform:
    translateX(
      clamp(
        -70%,
        -50%,
        calc(50vw - 100%)
      )
    )
    translateY(-20px);
}

.mega-menu.align-right {
  left: auto;
  right: 0;
}

.mega-menu.open {
  opacity:1;
  transform:translateY(0);
  pointer-events:auto;
}

.mega-menu.smart-align.open {
  opacity: 1;
  pointer-events: auto;
  transform:
    translateX(
      clamp(
        -70%,
        -50%,
        calc(50vw - 100%)
      )
    )
    translateY(0);
}

.mega-grid {
  display:grid;
  grid-template-columns:1.2fr 1fr;
  gap:60px;
}

.mega-left h2 {
  font-size:2.2rem;
  font-weight:800;
}
.mega-left p {
  color:#555;
  line-height:1.7;
  margin:20px 0 30px;
}
.mega-btn {
  display:inline-block;
  padding:14px 24px;
  border-radius:999px;
  border:1px solid #ddd;
  font-weight:600;
  text-decoration:none;
  color:#000;
}

.mega-right h6 {
  font-weight:700;
  margin-bottom:20px;
}
.mega-right ul {
  list-style:none;
  padding:0;
}
.mega-right li {
  padding:10px 0;
  font-weight:600;
}

.menu-muted {
  color:#aaa;
  font-weight:600;
}

/* Hover underline (no opening menu) */
.menu-item {
  position: relative;
}

.menu-item::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -12px;
  width: 0;
  height: 3px;
  background: #ff4d1c;
  border-radius: 4px;
  transition: width 0.25s ease;
}

.menu-item:hover::after {
  width: 80px;
}

/* Active underline (menu open) */
.underline.active {
  position: absolute;
  bottom: -12px;
  left: 0;
  width: 80px;
  height: 3px;
  background: #ff4d1c;
  border-radius: 4px;
}

.demo-btn {
  background:#ff4d1c;
  color:#fff;
  padding:10px 22px;
  border-radius:999px;
  font-weight:600;
}

/* Clickable list items */
.mega-right a {
  display: block;
  color: #000;
  text-decoration: none;
  padding: 10px 0;
  font-weight: 600;
}

.mega-right a:hover {
  color: #ff4d1c;
}

/* Solutions mega menu layout */
.mega-menu.solutions {
  width: 700px;
  left: -150px;
}

.solutions-grid {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 40px;
  align-items: center;
}

.solutions-grid img {
  width: 100%;
  border-radius: 16px;
}

.solutions-grid h3 {
  font-size: 1.8rem;
  font-weight: 800;
  margin-bottom: 16px;
}

.solutions-grid p {
  color: #555;
  line-height: 1.6;
}
</style>

<template>
  <nav class="navbar bg-white border-bottom">
    <div class="container-fluid px-5">

      <!-- Logo -->
      <router-link class="navbar-brand fw-bold d-flex align-items-center" to="/">
        <span class="logo">tixly</span><span class="dot">:</span>
      </router-link>

      <!-- <div class="d-flex align-items-center gap-3 ms-4">
        <router-link to="/" class="menu-btn">Home</router-link>
        <router-link to="/tickets" class="menu-btn">Tickets</router-link>
      </div> -->

      <!-- Menu -->
      <ul class="navbar-nav flex-row gap-5 align-items-center">

        <!-- TICKETS -->
         <li class="nav-item position-relative menu-item" ref="moviesMenu">
          <router-link to="/movies" class="menu-btn" :class="{ 'menu-muted': activeMenu && activeMenu !== 'movies' }" @click.stop="toggle('movies')">Movies
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'movies' }"></i>
          </router-link>
          <span class="underline active" v-if="activeMenu === 'movies'"></span>
        </li>

        <!-- <li class="nav-item position-relative menu-item" ref="productMenu">
          <router-link to="/tickets" class="menu-btn" :class="{ 'menu-muted': activeMenu && activeMenu !== 'product' }" @click.stop="toggle('product')">Tickets
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'product' }"></i>
          </router-link> -->

          <!-- underline (ACTIVE / OPEN) -->

          <!-- MEGA MENU
          <div class="mega-menu" :class="{ open: activeMenu === 'product' }">
            <div class="mega-grid"> -->

              <!-- LEFT -->
              <!-- <div class="mega-left">
                <h2>Product overview</h2>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
                <a href="#" class="mega-btn">View all features →</a>
              </div> -->

              <!-- RIGHT -->
              <!-- <div class="mega-right">
                <h6>Features</h6>
                <ul>
                  <li><a href="#">Online sales</a></li>
                  <li><a href="#">Box office sales</a></li>
                  <li><a href="#">Administration</a></li>
                  <li><a href="#">Reporting & analytics</a></li>
                  <li><a href="#">Marketing</a></li>
                  <li><a href="#">Scanning</a></li>
                  <li><a href="#">Integrations</a></li>
                  <li><a href="#">Pricing</a></li>
                </ul>
              </div>

            </div>
          </div> -->
        <!-- </li> -->

        <!-- YOUR TICKETS -->
        <li class="nav-item position-relative menu-item" ref="solutionsMenu">
          <router-link to="/userTickets" class="menu-btn" :class="{ 'menu-muted': activeMenu && activeMenu !== 'solutions' }" @click.stop="toggle('solutions')">Your Tickets
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'solutions' }"></i>
          </router-link>

          <!-- underline -->
          <span class="underline active" v-if="activeMenu === 'solutions'"></span>

          <!-- SOLUTIONS MEGA MENU
          <div class="mega-menu solutions" :class="{ open: activeMenu === 'solutions' }">
            <div class="solutions-grid">

              <div>
                <h3>Solutions overview</h3>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Vivamus lacinia odio vitae vestibulum vestibulum.
                </p>
              </div>

            </div>
          </div> -->
        </li>

        <li>
          <router-link to="/schedule" class="menu-btn">
            FUN
          </router-link>
        </li>
        
        <!-- ABOUT US -->
        <li class="nav-item position-relative menu-item" ref="clientsMenu">
          <router-link to="/aboutUs" class="menu-btn" :class="{ 'menu-muted': activeMenu && activeMenu !== 'clients' }" @click.stop="toggle('clients')">
            About Us
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'clients' }"></i>
          </router-link>

          <span class="underline active" v-if="activeMenu === 'clients'"></span>

          <!-- <div class="mega-menu smart-align" :class="{ open: activeMenu === 'clients' }"> -->
            <!-- EMPTY MEGA MENU -->
          <!-- </div> -->
        </li>

        
        

        <!-- RESOURCES -->
        <!-- <li class="nav-item position-relative menu-item" ref="resourcesMenu"> -->
          <!-- <button
            class="menu-btn"
            :class="{ 'menu-muted': activeMenu && activeMenu !== 'resources' }"
            @click.stop="toggle('resources')"
          >
            Resources
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'resources' }"></i>
          </button>

          <span class="underline active" v-if="activeMenu === 'resources'"></span> -->

          <!-- <div class="mega-menu smart-align" :class="{ open: activeMenu === 'resources' }"> -->
            <!-- EMPTY MEGA MENU -->
          <!-- </div> -->
        <!-- </li> -->

        <!-- COMPANY -->
        <!-- <li class="nav-item position-relative menu-item" ref="companyMenu"> -->
          <!-- <button
            class="menu-btn"
            :class="{ 'menu-muted': activeMenu && activeMenu !== 'company' }"
            @click.stop="toggle('company')"
          >
            Company
            <i class="bi bi-chevron-down" :class="{ rotate: activeMenu === 'company' }"></i>
          </button>

          <span class="underline active" v-if="activeMenu === 'company'"></span> -->

          <!-- <div class="mega-menu smart-align" :class="{ open: activeMenu === 'company' }"> -->
            <!-- EMPTY MEGA MENU -->
          <!-- </div> -->
        <!-- </li> -->
      </ul>

      <!-- Right -->
      <div class="d-flex align-items-center gap-3">
        <router-link to="/accountPage">
          <!-- <a class="btn demo-btn"></a> -->
          <i class="bi bi-person-circle fs-5 demo-btn"></i>
        </router-link>
        <!-- <i class="bi bi-search fs-5"></i> -->
      </div>

    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      activeMenu: null // 'product' | 'solutions' | 'clients' | 'resources' | 'company'
    }
  },
  mounted() {
    document.addEventListener("click", this.handleOutsideClick)
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleOutsideClick)
  },
  methods: {
    toggle(menu) {
      this.activeMenu = this.activeMenu === menu ? null : menu
    },
    handleOutsideClick(e) {
      const menus = [
        this.$refs.productMenu,
        this.$refs.solutionsMenu,
        this.$refs.clientsMenu,
        this.$refs.resourcesMenu,
        this.$refs.companyMenu
      ]

      if (menus.some(m => m && m.contains(e.target))) return

      this.activeMenu = null
    }
  }
}
</script>



