<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Giga Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>

    <header class="header">
        <div class="logo-container">
            <img src="../assets/images/logo.png" alt="Giga Store Logo" class="main-logo-img" width="100">
        </div>
        <nav class="header-nav">
            <a href="index.php" class="nav-link">Home</a>
            <a href="register.php" class="nav-link login-btn">Register</a>
        </nav>
    </header>

    <main class="main-container">
        <div class="login-layout">

            <div class="info-section">
                <div class="logo-image-container">
                    <img src="../assets/images/logo.png" alt="Giga Store Logo" class="main-logo-img" width="500">
                </div>

                <h1 class="sign-in-title">Sign in</h1>
                <p class="sign-in-text">
                    Access your orders, track shipments, and manage your account.
                </p>
            </div>

            <div class="login-card">
                <h2>Login</h2>

                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 15px;">'
                        . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 15px;">'
                        . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>

                <form action="../controllers/AuthController.php" method="post">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>

                    <div class="form-group password-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="show-password-btn">Show</button>
                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="sign-in-btn">Sign in</button>

                    <p class="terms-text">
                        By continuing you agree to our <a href="#" class="terms-link">Terms & Privacy.</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content-revised">

            <div class="footer-section-item footer-info">
                <h4>Giga Store</h4>
                <p>Modern store experience. Crafted with care.</p>
            </div>

            <div class="footer-section-item">
                <h4>About</h4>
                <a href="#">Blog</a>
                <a href="#">Team</a>
            </div>

            <div class="footer-section-item">
                <h4>Support</h4>
                <a href="#">Contact</a>
                <a href="#">FAQ</a>
            </div>

            <div class="footer-section-item copyright-section">
                <p>© 2025 Giga Store. All rights reserved.</p>
            </div>

        </div>
    </footer>

    <script>
        document.querySelectorAll('.password-group .show-password-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const group = btn.closest('.password-group');
                if (!group) return;
                const input = group.querySelector('input[type="password"], input[type="text"]');
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.textContent = isPassword ? 'Hide' : 'Show';
                btn.setAttribute('aria-pressed', String(isPassword));
            });
        });
    </script>
</body>

</html>