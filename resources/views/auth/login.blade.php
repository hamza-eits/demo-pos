<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XT BOOKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
        }
        .logo-section {
            background: linear-gradient(135deg, #ffffff, #e0f0ff);
            color: black;
        }
        .logo-text {
            font-size: 2rem;
            font-weight: bold;
        }
        .form-section {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container-fluid login-container d-flex align-items-center justify-content-center">
    <div class="row w-100 shadow rounded overflow-hidden" style="max-width: 900px;">

        <!-- Left Logo Section -->
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center logo-section p-5">
            <div class="text-center">
                <img src="{{ asset('logo-3.png') }}" alt="Logo" class="img-fluid mb-3">
                <div class="logo-text">POS Solution</div>
                <p class="mt-3">Welcome back! Please login to your account.</p>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="col-md-6 col-12 p-5 bg-white">
            <h3 class="mb-4">Login</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <!-- Forgot Password & Submit -->
                <div class="d-flex justify-content-between align-items-center ">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none d-none">Forgot your password?</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordInput = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        icon.classList.toggle("bi-eye");
        icon.classList.toggle("bi-eye-slash");
    });
</script>

</body>
</html>
