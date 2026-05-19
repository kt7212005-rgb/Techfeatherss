<?php
// controllers/LoginController.php
require_once __DIR__ . '/BaseController.php';

class LoginController extends BaseController {
    public function index() {
        if (!empty($_SESSION['user'])) {
            $this->redirect('/dashboard.php');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Please enter your email and password.';
            } elseif (login_user($email, $password)) {
                $this->redirect('/dashboard.php');
            } else {
                $error = 'Invalid email or password.';
            }
        }

        $this->render('login', ['error' => $error]);
    }
}
?>