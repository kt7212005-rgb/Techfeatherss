<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Poultry Management - Forgot Password</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-card card">
            <form class="form" method="post" novalidate>
                <h1>FORGOT PASSWORD</h1>

                <?php
                $error = $error ?? '';
                $success = $success ?? '';
                ?>

                <?php if ($error): ?>
                    <div class="message" style="background: #e74c3c; color: #fff;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="message" style="background: #2ecc71; color: #fff;"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="your@email.com" required />
                </div>

                <button class="button" type="submit">Send Reset Instructions</button>

                <div style="text-align: center; margin-top: 16px;">
                    <a href="index.php" style="color: var(--muted); text-decoration: none;">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
