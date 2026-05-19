<?php
// controllers/ChangePasswordController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';

class ChangePasswordController extends BaseController {
    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function index() {
        require_login();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = trim($_POST['current_password'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            $user = current_user();
            $dbUser = $this->model->getUserByEmail($user['email']);

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                $error = 'Please fill in all fields.';
            } elseif (!$dbUser || !password_verify($currentPassword, $dbUser['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'New password must be at least 6 characters long.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match.';
            } else {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->model->updatePassword($user['id'], $hash);
                $success = 'Password changed successfully.';
            }
        }

        $this->render('change_password', [
            'error' => $error,
            'success' => $success,
            'pageTitle' => 'Change Password',
            'pageDescription' => 'Update your account password.',
            'activeNav' => 'password'
        ]);
    }
}
?>