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
      <div class="row gy-5">

        <!-- Brand -->
        <div class="col-12 col-lg-3">
          <div class="mb-4">
            <h5 class="mb-3">Intuitive ticketing<br>presented in the<br><span class="text-danger">simplest</span> way</h5>
          </div>
          <button class="btn btn-light btn-sm d-inline-flex align-items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4L8 8L14 4M2 4V12C2 12.5523 2.44772 13 3 13H13C13.5523 13 14 12.5523 14 12V4M2 4C2 3.44772 2.44772 3 3 3H13C13.5523 3 14 3.44772 14 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Join our Mailing List
          </button>
        </div>

        <!-- Product -->
        <div class="col-6 col-md-3 col-lg-2">
          <h6 class="fw-bold mb-3">Product</h6>
          <ul class="list-unstyled footer-links">
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Product overview</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Clients</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Pricing</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Certifications</a></li>
          </ul>
        </div>

        <!-- Features -->
        <div class="col-6 col-md-3 col-lg-2">
          <h6 class="fw-bold mb-3">Features</h6>
          <ul class="list-unstyled footer-links">
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Online sales</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Box office</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Reporting</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Administration</a></li>
          </ul>
        </div>

        <!-- Resources -->
        <div class="col-6 col-md-3 col-lg-2">
          <h6 class="fw-bold mb-3">Resources</h6>
          <ul class="list-unstyled footer-links">
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Blog</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Help center</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">App downloads</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Partners</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div class="col-6 col-md-3 col-lg-2">
          <h6 class="fw-bold mb-3">Company</h6>
          <ul class="list-unstyled footer-links">
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">About us</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Meet the team</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Careers</a></li>
            <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Contact us</a></li>
          </ul>
        </div>

      </div>

      <!-- Bottom -->
      <div class="row mt-5 pt-4 border-top align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
          <div class="d-flex gap-3">
            <a href="#" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-facebook"></i>
              </div>
            </a>
            <a href="#" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-twitter-x"></i>
              </div>
            </a>
            <a href="#" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-linkedin"></i>
              </div>
            </a>
            <a href="#" class="text-decoration-none">
              <div class="social-icon rounded-circle bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-youtube"></i>
              </div>
            </a>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="d-flex flex-wrap justify-content-md-end gap-3 text-muted small">
            <a href="#" class="text-muted text-decoration-none">Privacy policy</a>
            <a href="#" class="text-muted text-decoration-none">Accessibility</a>
            <a href="#" class="text-muted text-decoration-none">Product updates</a>
            <a href="#" class="text-muted text-decoration-none">Brand portal</a>
            <span class="ms-md-3">© 2025 Tixly. All rights reserved.</span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>