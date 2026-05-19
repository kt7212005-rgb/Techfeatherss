<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Poultry Management - Login</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-card card">
            <form class="form" method="post" novalidate>
                <h1>TECHFEATHERS LOGIN</h1>

                <?php $error = $error ?? ''; ?>

                <?php if ($error): ?>
                    <div class="message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="admin@poultry.local" required autofocus />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Password" required />
                </div>

                <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <a href="forgot_password.php" style="font-size:0.9rem;">Forgot Password?</a>
                </div>

                <button class="button" type="submit">Log In</button>

                <div style="text-align: center; margin-top: 16px;">
                    <a href="register.php" style="color: var(--muted); text-decoration: none;">Don't have an account? Create one here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>