<?php
// controllers/ProductController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController extends BaseController {
    private ProductModel $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function index() {
        require_admin();

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_product'])) {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = (float) ($_POST['price'] ?? 0);
                $category = trim($_POST['category'] ?? '');
                $quantity = (int) ($_POST['quantity'] ?? 0);

                if ($name && $price > 0 && $category) {
                    $this->model->addProduct($name, $description, $price, $category, $quantity);
                    $message = 'Product added successfully.';
                } else {
                    $message = 'Please fill in all required fields.';
                }
            } elseif (isset($_POST['update_product'])) {
                $id = (int) ($_POST['id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = (float) ($_POST['price'] ?? 0);
                $category = trim($_POST['category'] ?? '');
                $quantity = (int) ($_POST['quantity'] ?? 0);

                if ($id && $name && $price > 0 && $category) {
                    $this->model->updateProduct($id, $name, $description, $price, $category, $quantity);
                    $message = 'Product updated successfully.';
                } else {
                    $message = 'Please fill in all required fields.';
                }
            } elseif (isset($_POST['delete_product'])) {
                $id = (int) ($_POST['id'] ?? 0);
                if ($id) {
                    $this->model->deleteProduct($id);
                    $message = 'Product deleted successfully.';
                }
            }
        }

        $data = [
            'message' => $message,
            'products' => $this->model->getAllProducts(),
            'pageTitle' => 'Product Management',
            'pageDescription' => 'Add, edit, and manage products for ordering.',
            'activeNav' => 'products'
        ];

        $this->render('products', $data);
    }
}
?>