<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tixly Login</title>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    body {
      background: #ffffff;
      color: #000;
    }

    /* HERO */
    .hero {
      padding: 5rem 2rem;
      display: flex;
      justify-content: center;
    }

    .hero-content {
      max-width: 900px;
      text-align: center;
    }

    .hero-title {
      font-size: clamp(2.5rem, 5vw, 4.5rem);
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 1.5rem;
    }

    .dot {
      color: #ff4d1c;
    }

    .hero-description {
      font-size: 1.05rem;
      line-height: 1.7;
      color: #222;
      max-width: 760px;
      margin: 0 auto;
    }

    /* SECTION */
    .info-section {
      max-width: 1200px;
      margin: 4rem auto;
      padding: 0 2rem;
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 4rem;
      align-items: start;
    }

    /* LEFT CONTENT */
    .expect-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    .expect-item {
      margin-bottom: 2rem;
    }

    .expect-item h4 {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: .5rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .expect-item h4::before {
      content: "✔";
      color: #ff4d1c;
      font-weight: bold;
    }

    .expect-item p {
      color: #444;
      line-height: 1.6;
      font-size: .95rem;
    }

    /* AUTH CARD */
    .login-card {
      background: #fff;
      border-radius: 20px;
      padding: 2.5rem;
      box-shadow: 0 20px 50px rgba(0,0,0,.08);
      position: relative;
      overflow: hidden;
    }

    .auth-panel {
      opacity: 0;
      transform: translateY(10px);
      pointer-events: none;
      transition: opacity .4s ease, transform .4s ease;
      position: absolute;
      inset: 0;
      padding: 2.5rem;
    }

    .auth-panel.active {
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
      position: relative;
    }

    h3 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.4rem;
    }

    label {
      display: block;
      font-size: .85rem;
      font-weight: 600;
      margin-bottom: .4rem;
    }

    input {
      width: 100%;
      padding: .75rem 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: .95rem;
    }

    input:focus {
      outline: none;
      border-color: #ff4d1c;
    }

    .login-btn {
      width: 100%;
      padding: .9rem;
      background: #ff4d1c;
      border: none;
      color: #fff;
      font-weight: 600;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 1rem;
    }

    .login-btn:hover {
      background: #e64317;
    }

    .login-footer {
      text-align: center;
      font-size: .85rem;
      margin-top: 1.2rem;
      color: #555;
      cursor: pointer;
    }

    .login-footer span {
      color: #ff4d1c;
      font-weight: 600;
    }

    /* PASSWORD FIELD */
    .password-wrapper {
    position: relative;
    }

    .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: .85rem;
    cursor: pointer;
    color: #888;
    user-select: none;
    }

    .toggle-password:hover {
    color: #ff4d1c;
    }

    /* STRENGTH METER */
    .strength-meter {
    margin-top: .4rem;
    }

    .strength-bar {
    height: 6px;
    width: 100%;
    background: #eee;
    border-radius: 10px;
    overflow: hidden;
    }

    .strength-fill {
    height: 100%;
    width: 0%;
    background: red;
    transition: width .3s ease, background .3s ease;
    }

    .strength-text {
    font-size: .75rem;
    margin-top: .25rem;
    color: #666;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .info-section {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <h1 class="hero-title">
      See what Tixly can<br>
      do for your venue<span class="dot">.</span>
    </h1>
    <p class="hero-description">
      In the meeting, we'll focus on understanding your venue's unique needs.
    </p>
  </div>
</section>

<!-- INFO + AUTH -->
<section class="info-section">

  <!-- LEFT -->
  <div>
    <h2 class="expect-title">What to expect in Tixly</h2>

    <div class="expect-item">
      <h4>Understanding Your Goals</h4>
      <p>We discuss goals and tailor Tixly to your needs.</p>
    </div>

    <div class="expect-item">
      <h4>Customised Expert Advice</h4>
      <p>Advice on pricing, integrations, and implementation.</p>
    </div>

    <div class="expect-item">
      <h4>Personalised Demo Planning</h4>
      <p>A demo that fits your venue perfectly.</p>
    </div>
  </div>

  <!-- RIGHT AUTH -->
  <div class="login-card">

    <!-- LOGIN -->
    <div id="loginPanel" class="auth-panel active">
      <h3>Login</h3>

      <form id="loginForm">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="password-wrapper">
            <input type="password" name="password" id="loginPassword" required>
            <span class="toggle-password"
                    onclick="togglePassword('loginPassword', this)">Show</span>
            </div>
        </div>

        <button type="submit" class="login-btn">Sign in</button>

        <div class="login-footer" onclick="showRegister()">
            Don’t have an account? <span>Register</span>
        </div>
      </form>
    </div>

    <!-- REGISTER -->
    <div id="registerPanel" class="auth-panel">
        <h3>Register</h3>

        <form id="registerForm">
            <div class="form-group">
            <label>Name</label>
            <input
                type="text"
                name="name"
                placeholder="Your full name"
                required
            >
            </div>

            <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="you@example.com"
                required
            >
            </div>

            <div class="form-group">
              <label>Phone Number</label>
              <input
                type="tel"
                name="phone"
                placeholder="+62 812 3456 7890"
                required
              >
            </div>

            <div class="form-group">
            <label>Password</label>

            <div class="password-wrapper">
                <input
                type="password"
                id="registerPassword"
                name="password"
                placeholder="Create a password"
                required
                oninput="checkStrength(this.value)"
                >
                <span
                class="toggle-password"
                onclick="togglePassword('registerPassword', this)"
                >
                Show
                </span>
            </div>

            <div class="strength-meter">
                <div class="strength-bar">
                <div id="strengthFill" class="strength-fill"></div>
                </div>
                <div id="strengthText" class="strength-text">
                Password strength
                </div>
            </div>
            </div>

            <button type="submit" class="login-btn">
            Create account
            </button>

            <div class="login-footer" onclick="showLogin()">
            Already have an account? <span>Login</span>
            </div>
        </form>
    </div>

  </div>
</section>

<script> // Toggle Panels
  function showRegister() {
    document.getElementById('loginPanel').classList.remove('active');
    document.getElementById('registerPanel').classList.add('active');
  }

  function showLogin() {
    document.getElementById('registerPanel').classList.remove('active');
    document.getElementById('loginPanel').classList.add('active');
  }
</script>

<script> // Password Toggle & Strength Meter
  function togglePassword(inputId, toggleEl) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
      input.type = "text";
      toggleEl.textContent = "Hide";
    } else {
      input.type = "password";
      toggleEl.textContent = "Show";
    }
  }

  function checkStrength(password) {
    const fill = document.getElementById("strengthFill");
    const text = document.getElementById("strengthText");

    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    if (password.length === 0) {
      fill.style.width = "0%";
      text.textContent = "Password strength";
      return;
    }

    if (strength <= 1) {
      fill.style.width = "25%";
      fill.style.background = "#e74c3c";
      text.textContent = "Weak";
    } else if (strength === 2 || strength === 3) {
      fill.style.width = "60%";
      fill.style.background = "#f39c12";
      text.textContent = "Medium";
    } else {
      fill.style.width = "100%";
      fill.style.background = "#2ecc71";
      text.textContent = "Strong";
    }
  }
</script>

<script> // Register Form Submission
document.getElementById("registerForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/register", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
    },
    body: formData
  })
  .then(res => res.text())
  .then(result => {
    if (result === "success") {
      alert("Registration successful. Please login.");
      showLogin();
    } else if (result === "email_exists") {
      alert("Email already registered.");
    } else {
      alert("Registration failed.");
    }
  });
});
</script>

<script> // Login Form Submission Script
document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/login", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content")
    },
    body: formData
  })
  .then(res => res.json())
  .then(result => {
    if (result.status === "success") {

      // Show session info alert (bisa di comment kalau gak perlu)
      // alert(
      //   "Login successful!\n\n" +
      //   "User ID: " + result.user_id + "\n" +
      //   "Name: " + result.user_name + "\n" +
      //   "Role: " + result.role
      // );
      sessionStorage.setItem('user_id', result.user_id);
      // Role-based redirect
      if (result.role === "admin") {
        window.location.href = "/admin"; // Redirect to admin dashboard
      } else {
        window.location.href = "/"; // Redirect to user homepage
      }

    } else {
      alert("Invalid email or password");
    }
  })
  .catch(err => {
    console.error(err);
    alert("Login error occurred");
  });
});
</script>


</body>
</html>
