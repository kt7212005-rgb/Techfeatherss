<?php
// controllers/UserController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends BaseController {
    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function index() {
        require_admin();

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'manager');

            if ($email === '' || $name === '' || $password === '') {
                $message = 'Please fill in all fields.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $this->model->addUser($email, $hash, $name, $role);
                $message = 'User created successfully.';
            }
        }

        $data = [
            'message' => $message,
            'users' => $this->model->getUsers(),
            'pageTitle' => 'Users',
            'pageDescription' => 'Show all registered account and their roles in the system.',
            'activeNav' => 'users'
        ];

        $this->render('user', $data);
    }
}
?>