<?php
// controllers/AdminOrderController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/OrderModel.php';

class AdminOrderController extends BaseController {
    private OrderModel $model;

    public function __construct() {
        $this->model = new OrderModel();
    }

    public function index() {
        require_admin();

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int) ($_POST['order_id'] ?? 0);
            $action = $_POST['action'] ?? '';

            if ($orderId > 0 && in_array($action, ['approved', 'rejected', 'pending'])) {
                $this->model->updateOrderStatus($orderId, $action);
                $message = 'Order status updated successfully.';
            }
        }

        $data = [
            'message' => $message,
            'orders' => $this->model->getAllOrders(),
            'pageTitle' => 'Order Management',
            'pageDescription' => 'Review and manage customer orders.',
            'activeNav' => 'orders'
        ];

        $this->render('admin_orders', $data);
    }
}
?>