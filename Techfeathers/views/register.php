<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Poultry Management - Register</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-card card">
            <form class="form" method="post" novalidate>
                <h1>CREATE ACCOUNT</h1>

                <?php
                $error = $error ?? '';
                $success = $success ?? '';
                $formData = $formData ?? ['name' => '', 'email' => ''];
                ?>

                <?php if ($error): ?>
                    <div class="message" style="background: #e74c3c; color: #fff;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="message" style="background: #2ecc71; color: #fff;"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="field">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" type="text" placeholder="Your full name" value="<?= htmlspecialchars($formData['name']) ?>" required />
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="your@email.com" value="<?= htmlspecialchars($formData['email']) ?>" required />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Password (min 6 characters)" required />
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm password" required />
                </div>

                <button class="button" type="submit">Create Account</button>

                <div style="text-align: center; margin-top: 16px;">
                    <a href="index.php" style="color: var(--muted); text-decoration: none;">Already have an account? Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>