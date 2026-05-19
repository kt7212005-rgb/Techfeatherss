<?php
// controllers/FinanceController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/FinanceModel.php';

class FinanceController extends BaseController {
    private FinanceModel $model;

    public function __construct() {
        $this->model = new FinanceModel();
    }

    public function index() {
        require_role(['admin', 'manager']);

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = ($_POST['type'] ?? 'sale') === 'expense' ? 'expense' : 'sale';
            $description = trim($_POST['description'] ?? '');
            $amount = (float) ($_POST['amount'] ?? 0);
            $incurredAt = $_POST['incurred_at'] ?? date('Y-m-d');

            if ($description === '' || $amount <= 0) {
                $message = 'Please enter a description and a valid amount.';
            } else {
                $this->model->addTransaction($type, $description, $amount, $incurredAt);
                $message = 'Transaction recorded.';
            }
        }

        $data = [
            'message' => $message,
            'transactions' => $this->model->getTransactions(),
            'pageTitle' => 'Finance',
            'pageDescription' => 'Track sales and expenses for your poultry operations.',
            'activeNav' => 'finance'
        ];

        $this->render('finance', $data);
    }
}
?>