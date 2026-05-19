<?php
// controllers/ForgotPasswordController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';

class ForgotPasswordController extends BaseController {
    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function index() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if ($email === '') {
                $error = 'Please enter your email address.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                $user = $this->model->getUserByEmail($email);
                if ($user) {
                    $success = 'If that email exists, password reset instructions have been sent.';
                } else {
                    $success = 'If that email exists, password reset instructions have been sent.';
                }
            }
        }

        $this->render('forgot_password', [
            'error' => $error,
            'success' => $success,
            'pageTitle' => 'Forgot Password',
            'pageDescription' => 'Enter your email to receive password reset instructions.',
            'activeNav' => 'forgot'
        ]);
    }
}
