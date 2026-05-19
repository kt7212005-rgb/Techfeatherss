<?php
require_once __DIR__ . '/controllers/ReportController.php';

$controller = new ReportController();

$action = $_GET['action'] ?? 'index';

if ($action === 'generate') {
    $controller->generate();
} else {
    $controller->index();
}
?>
