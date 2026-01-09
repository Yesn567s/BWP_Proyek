<style scoped>
.tixly-footer {
  background: transparent;
}

.footer-bg {
  background-color: #f2f2f2;
  width: 100vw;
  margin: 0 auto;         /* center it */
  border-radius: 20px;    /* soft edges */
  padding: 3rem 1.5rem;   /* inner spacing */
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); /* subtle softness */
  
}

.footer-bg {
  background: linear-gradient(
    180deg,
    #f6f6f6 0%,
    #f2f2f2 100%
  );
}

.footer-links a {
  color: #333;
  transition: color 0.25s ease, opacity 0.25s ease;
}

.footer-links a:hover {
  color: #666;
  opacity: 0.7;
  text-decoration: none;
}

.social-icon {
  width: 40px;
  height: 40px;
  font-size: 18px;
  color: #333;
  transition: all 0.3s ease;
}

.social-icon:hover {
  background-color: #e8e8e8 !important;
  transform: translateY(-2px);
}

/* Bottom footer links */
.d-flex a.text-muted {
  transition: color 0.25s ease, opacity 0.25s ease;
}

.d-flex a.text-muted:hover {
  color: #333 !important; /* unmute */
  opacity: 1;
  text-decoration: underline;
}
</style>


<template>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <footer class="tixly-footer py-5">
    <div class="footer-bg">
      <div class="row gy-5 align-items-start mx-3 px-3">

        <!-- Brand / socials -->
        <div class="col-12 col-lg-3 d-flex flex-column align-items-center text-center gap-3 order-2 order-lg-1">
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://www.facebook.com/tixid/" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-facebook"></i>
              </div>
            </a>
            <a href="https://x.com/tix_id?lang=en" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-twitter-x"></i>
              </div>
            </a>
            <a href="https://www.instagram.com/tix_id/?hl=en" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-instagram"></i>
              </div>
            </a>
            <a href="https://www.youtube.com/@TIX_ID" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-youtube"></i>
              </div>
            </a>
          </div>
          <button class="btn btn-light btn-sm d-inline-flex align-items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4L8 8L14 4M2 4V12C2 12.5523 2.44772 13 3 13H13C13.5523 13 14 12.5523 14 12V4M2 4C2 3.44772 2.44772 3 3 3H13C13.5523 3 14 3.44772 14 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            tixxLy@gmail.com
          </button>
        </div>

        <div class="col-12 col-lg-9 order-1 order-lg-2 text-center">
          <div class="row justify-content-lg-end text-lg-end">
            <!-- Tickets -->
            <div class="col-6 col-md-4 col-lg-3 mb-4 mb-lg-0 text-center">
              <h6 class="fw-bold mb-3">Tickets</h6>
              <ul class="list-unstyled footer-links">
                <router-link to="/movies">
                  <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Movies</a></li>
                </router-link to="/fun">
                <li class="mb-2"><a href="#" class="text-decoration-none text-dark">FUNly</a></li>
              </ul>
            </div>

            <!-- Resources -->
            <div class="col-6 col-md-4 col-lg-3 mb-4 mb-lg-0 text-center">
              <h6 class="fw-bold mb-3">Media</h6>
              <ul class="list-unstyled footer-links">
                <router-link to="/blog">
                  <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Blogs</a></li>
                </router-link>
                <li class="mb-2"><a href="https://www.facebook.com/tixid/" class="text-decoration-none text-dark">FaceBook</a></li>
                <li class="mb-2"><a href="https://www.instagram.com/tix_id/?hl=en" class="text-decoration-none text-dark">Instagram</a></li>
                <li class="mb-2"><a href="https://www.youtube.com/@TIX_ID" class="text-decoration-none text-dark">YouTube</a></li>
                <li class="mb-2"><a href="https://x.com/tix_id?lang=en" class="text-decoration-none text-dark">Twitter</a></li>
              </ul>
            </div>

            <!-- Company -->
            <div class="col-6 col-md-4 col-lg-3 text-center">
              <h6 class="fw-bold mb-3">Company</h6>
              <ul class="list-unstyled footer-links">
                <router-link to="/about-us">
                  <li class="mb-2"><a class="text-decoration-none text-dark">About us</a></li>
                </router-link>
                <router-link to="/blog">
                  <li class="mb-2"><a class="text-decoration-none text-dark">Our Stories</a></li>
                </router-link>
              </ul>
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom -->
      <div class="row mt-5 pt-4 border-top bg-black text-white">
        <div class="col-12">
          <div class="d-flex flex-wrap justify-content-center text-center gap-3 text-muted small">
            <span>© 2025 Tixly. All rights reserved.</span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>