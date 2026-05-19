<?php
// controllers/RegisterController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../includes/auth.php';

class RegisterController extends BaseController {
    public function index() {
        $error = '';
        $success = '';
        $formData = [
            'name' => '',
            'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            $formData['name'] = $name;
            $formData['email'] = $email;

            if ($email === '' || $name === '' || $password === '') {
                $error = 'Please fill in all fields.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                if (register_user($email, $password, $name)) {
                    $success = 'Registration successful. You may now log in.';
                    $formData = ['name' => '', 'email' => ''];
                } else {
                    $error = 'Email already exists or registration failed.';
                }
            }
        }

        $this->render('register', [
            'error' => $error,
            'success' => $success,
            'formData' => $formData
        ]);
    }
}
?>