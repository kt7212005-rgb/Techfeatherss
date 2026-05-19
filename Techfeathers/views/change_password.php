<?php include __DIR__ . '/../includes/header.php'; ?>

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

<div class="card-panel">
    <h2>Change Password</h2>
    <form method="post" style="display:grid; gap:12px; max-width:480px;">
        <div class="field">
            <label for="current_password">Current Password</label>
            <input id="current_password" name="current_password" type="password" required />
        </div>
        <div class="field">
            <label for="new_password">New Password</label>
            <input id="new_password" name="new_password" type="password" required />
        </div>
        <div class="field">
            <label for="confirm_password">Confirm New Password</label>
            <input id="confirm_password" name="confirm_password" type="password" required />
        </div>
        <button class="button" type="submit">Change Password</button>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>