<?php
// controllers/ChickenController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ChickenModel.php';

class ChickenController extends BaseController {
    private ChickenModel $model;

    public function __construct() {
        $this->model = new ChickenModel();
    }

    public function index() {
        require_role(['admin', 'manager']);

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // New batch submission
            if (!empty($_POST['batch_submission'])) {
                $code = trim($_POST['batch_code'] ?? '');
                $breed = trim($_POST['breed'] ?? '');
                $quantity = (int) ($_POST['quantity'] ?? 0);
                $startedAt = $_POST['started_at'] ?? date('Y-m-d');

                if ($code === '' || $breed === '' || $quantity <= 0) {
                    $message = 'Please provide a batch code, breed, and quantity.';
                } else {
                    $this->model->addBatch($code, $breed, $quantity, $startedAt);
                    $message = 'Batch added successfully.';
                }
            }

            // Mortality recording submission
            if (!empty($_POST['mortality_submission'])) {
                $batchId = (int) ($_POST['mortality_batch'] ?? 0);
                $deaths = (int) ($_POST['mortality_count'] ?? 0);
                $reason = trim($_POST['mortality_reason'] ?? '');
                $notes = trim($_POST['mortality_notes'] ?? '');
                $recordedAt = $_POST['mortality_date'] ?? date('Y-m-d');

                if ($batchId <= 0 || $deaths < 0) {
                    $message = 'Please select a batch and enter a valid death count.';
                } else {
                    $this->model->addMortalityRecord($batchId, $deaths, $reason, $notes, $recordedAt);
                    $message = 'Mortality record saved.';
                }
            }
        }

        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';

        $viewMode = $_GET['view'] ?? 'list';
        if (!is_string($viewMode) || !in_array($viewMode, ['list', 'grid'], true)) {
            $viewMode = 'list';
        }

        $batches = $this->model->getFilteredBatches($search, $status);

        if (isset($_GET['export']) && $_GET['export'] === '1') {
            $filename = 'chicken_batches_' . date('Ymd_His') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['Batch Code', 'Breed', 'Started At', 'Quantity', 'Status']);

            foreach ($batches as $batch) {
                fputcsv($output, [
                    $batch['batch_code'],
                    $batch['breed'],
                    $batch['started_at'],
                    $batch['quantity'],
                    $batch['status'],
                ]);
            }

            fclose($output);
            exit;
        }

        $data = [
            'batches' => $batches,
            'mortalityRecords' => $this->model->getMortalityRecords(),
            'totalPopulation' => $this->model->getTotalPopulation(),
            'totalBreeds' => $this->model->getTotalBreeds(),
            'productionReady' => $this->model->getProductionReady(),
            'avgMortality' => $this->model->getAvgMortality(),
            'message' => $message,
            'search' => $search,
            'status' => $status,
            'viewMode' => $viewMode,
            'pageTitle' => 'Chickens',
            'pageDescription' => 'Manage batches of chickens and inventory status.',
            'activeNav' => 'chickens'
        ];

        $this->render('chicken', $data);
    }
}
?>