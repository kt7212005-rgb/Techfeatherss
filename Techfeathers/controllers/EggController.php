<?php
// controllers/EggController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/EggModel.php';

class EggController extends BaseController {
    private EggModel $model;

    public function __construct() {
        $this->model = new EggModel();
    }

    public function index() {
        require_role(['admin', 'manager']);

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $batchId = (int) ($_POST['batch_id'] ?? 0);
            $collectedAt = $_POST['collected_at'] ?? date('Y-m-d');
            $quantity = (int) ($_POST['quantity'] ?? 0);

            if ($batchId <= 0 || $quantity <= 0) {
                $message = 'Please select a batch and enter a valid egg count.';
            } else {
                $this->model->addEggRecord($batchId, $collectedAt, $quantity);
                $message = 'Egg count recorded.';
            }
        }

        $data = [
            'message' => $message,
            'batches' => $this->model->getBatches(),
            'eggRecords' => $this->model->getEggRecords(),
            'pageTitle' => 'Egg Production',
            'pageDescription' => 'Log daily egg collection and view historical production.',
            'activeNav' => 'eggs'
        ];

        $this->render('egg', $data);
    }
}
?>