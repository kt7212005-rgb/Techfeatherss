<?php
// includes/auth.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/UserModel.php';

function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function has_role($roles) {
    $user = current_user();
    if (!$user) {
        return false;
    }
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    return in_array($user['role'], $roles, true);
}

function require_role($roles) {
    require_login();
    if (!has_role($roles)) {
        header('Location: ' . BASE_URL . '/dashboard.php');
        exit;
    }
}

function require_admin() {
    require_role('admin');
}

function login_user($email, $password) {
    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID after successful login to reduce fixation risk
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        // Do not store password in session
        unset($user['password']);
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function register_user($email, $password, $name, $role = 'customer') {
    $userModel = new UserModel();
    if ($userModel->getUserByEmail($email)) {
        return false; // Email already exists
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    return $userModel->addUser($email, $hash, $name, $role);
}

function logout_user() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_unset();
    session_destroy();
}
