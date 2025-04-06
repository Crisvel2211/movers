<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"/>
  <style>
    body {
      background-image: url('images/background.jpg');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen backdrop-blur-sm bg-black/40">
  <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-8 rounded-xl shadow-lg space-y-6">
    <div class="flex justify-center">
      <img src="images/mover.jpg" alt="Logo" class="h-20 object-cover rounded-full"/>
    </div>

    <form id="loginForm" class="space-y-4">
      <div class="relative">
        <i class="uil uil-envelope absolute left-3 top-3 text-gray-500"></i>
        <input 
          type="email" 
          name="email" 
          id="email"
          placeholder="Email" 
          required 
          class="pl-10 pr-4 py-2 border rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>
      <div class="relative">
        <i class="uil uil-lock absolute left-3 top-3 text-gray-500"></i>
        <input 
          type="password" 
          name="password" 
          id="password"
          placeholder="Password" 
          required 
          class="pl-10 pr-10 py-2 border rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <i 
          id="togglePassword" 
          class="uil uil-eye absolute right-3 top-3 text-gray-500 cursor-pointer"
        ></i>
      </div>
      <div class="text-right text-sm">
        <a href="#" class="text-blue-600 hover:underline">Recover Password</a>
      </div>
      <button 
        type="submit" 
        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition"
      >
        Sign In
      </button>
    </form>
  </div>

  <!-- Toastify -->
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <script>
    // Toastify function
    function showToast(message, type) {
      Toastify({
        text: message,
        style: {
          background: type === 'success' 
            ? "linear-gradient(to right, #00b09b, #96c93d)" 
            : "linear-gradient(to right, #ff5f6d, #ffc371)"
        },
        duration: 3000,
        close: true
      }).showToast();
    }

    // Show/hide password
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      togglePassword.classList.toggle('uil-eye');
      togglePassword.classList.toggle('uil-eye-slash');
    });

    // Handle login form submit
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      try {
        const response = await fetch('https://logistic2.moverstaxi.com/api/loginApi.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (result.success) {
          showToast(`Welcome, ${result.username}!`, 'success');
          setTimeout(() => {
            window.location.href = result.redirect;
          }, 1500);
        } else if (result.error) {
          showToast(result.error, 'error');
        } else {
          showToast('Login failed. Please try again.', 'error');
        }
      } catch (error) {
        console.error('Login error:', error);
        showToast('An unexpected error occurred.', 'error');
      }
    });
  </script>
</body>
</html>
