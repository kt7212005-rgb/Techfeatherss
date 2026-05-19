<?php
// controllers/OrderController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController extends BaseController {
    private OrderModel $model;

    public function __construct() {
        $this->model = new OrderModel();
    }

    public function index() {
        require_login();

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int) ($_POST['product_id'] ?? 0);
            $quantity = (int) ($_POST['quantity'] ?? 0);

            if ($productId <= 0 || $quantity <= 0) {
                $message = 'Please select a product and enter a valid quantity.';
            } else {
                $user = current_user();
                $success = $this->model->placeOrder($user['id'], $productId, $quantity);
                if ($success) {
                    $message = 'Order placed successfully!';
                } else {
                    $message = 'Unable to place order. Please check stock availability and try again.';
                }
            }
        }

        $data = [
            'message' => $message,
            'products' => $this->model->getProducts(),
            'orders' => $this->model->getUserOrders(current_user()['id']),
            'pageTitle' => 'Place Order',
            'pageDescription' => 'Order fresh products from our farm.',
            'activeNav' => 'order'
        ];

        $this->render('order', $data);
    }
}
?>