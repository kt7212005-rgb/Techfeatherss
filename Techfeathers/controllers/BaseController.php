<?php
// controllers/BaseController.php
require_once __DIR__ . '/../includes/auth.php';

class BaseController {
    protected function render($view, $data = []) {
        extract($data);
        include __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
?>