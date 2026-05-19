<?php
// controllers/ReportController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/ReportModel.php';

class ReportController extends BaseController {
    private ReportModel $model;

    public function __construct() {
        $this->model = new ReportModel();
    }

    public function index() {
        require_role(['admin', 'manager']);

        $currentMonth = date('Y-m');
        $totalEggsThisMonth = $this->model->getTotalEggsForMonth($currentMonth);
        $feedConversion = $this->model->getFeedConversionRatio($currentMonth);
        $avgMortality = $this->model->getAverageMortalityRate($currentMonth);
        $trend = $this->model->getWeeklyEggTrend();
        $financialTrend = $this->model->getFinancialTrend();

        // static placeholder - in a real app, this would be stored in DB
        $recentExports = [
            ['name' => 'Financial_Summary.pdf', 'time' => '1 hour ago'],
            ['name' => 'Egg_Production.pdf', 'time' => '1 hour ago'],
        ];

        $data = [
            'totalEggsThisMonth' => $totalEggsThisMonth,
            'feedConversion' => $feedConversion,
            'avgMortality' => $avgMortality,
            'trendLabels' => $trend['labels'],
            'trendValues' => $trend['values'],
            'financialTrend' => $financialTrend,
            'recentExports' => $recentExports,
            'pageTitle' => 'Reports',
            'pageDescription' => 'Review production summaries and financial performance.',
            'activeNav' => 'reports'
        ];

        $this->render('report', $data);
    }

    public function generate() {
        require_role(['admin', 'manager']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/reports.php');
            exit;
        }

        $reportType = $_POST['report_type'] ?? 'batch_performance';
        $startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_POST['end_date'] ?? date('Y-m-d');
        $format = $_POST['format'] ?? 'json';

        $reportData = $this->model->generateReport($reportType, $startDate, $endDate, 'json');
        $data = json_decode($reportData, true);

        $filename = $reportType . '_report_' . date('Y-m-d');

        if ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="' . $filename . '.json"');
            $data['format'] = $format;
            $data['generated_at'] = date('Y-m-d H:i:s');
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        } elseif ($format === 'excel') {
            // Generate CSV for Excel
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            echo $this->generateCSV($data, $reportType, $startDate, $endDate);
            exit;
        } elseif ($format === 'pdf') {
            // Generate simple text-based PDF content
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $filename . '.txt"');
            echo $this->generateTextReport($data, $reportType, $startDate, $endDate);
            exit;
        }

        // Default to JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function generateCSV($data, $reportType, $startDate, $endDate) {
        $output = '';

        // Report header
        $output .= "Report Type:," . ucwords(str_replace('_', ' ', $reportType)) . "\n";
        $output .= "Generated At:," . date('Y-m-d H:i:s') . "\n";
        $output .= "Period:," . $startDate . " to " . $endDate . "\n";
        $output .= "\n";

        if ($reportType === 'batch_performance') {
            $output .= "Batch Performance Report\n\n";
            $output .= "Batch Code,Breed,Total Eggs,Avg Daily\n";

            if (isset($data['batches'])) {
                foreach ($data['batches'] as $batch) {
                    $output .= $batch['batch_code'] . ",";
                    $output .= $batch['breed'] . ",";
                    $output .= $batch['total_eggs'] . ",";
                    $output .= round($batch['avg_daily']) . "\n";
                }
            }

            $output .= "\nMortality Data\n\n";
            $output .= "Batch Code,Total Deaths\n";

            if (isset($data['mortality'])) {
                foreach ($data['mortality'] as $mortality) {
                    $output .= $mortality['batch_code'] . ",";
                    $output .= $mortality['total_deaths'] . "\n";
                }
            }
        } elseif ($reportType === 'financial') {
            $output .= "Financial Report\n\n";
            $output .= "Summary\n";
            $output .= "Total Income:,$" . number_format($data['summary']['total_income'], 2, '.', '') . "\n";
            $output .= "Total Expenses:,$" . number_format($data['summary']['total_expenses'], 2, '.', '') . "\n";
            $output .= "Net Profit:,$" . number_format($data['summary']['net_profit'], 2, '.', '') . "\n\n";

            $output .= "Transaction Details\n";
            $output .= "Date,Type,Description,Amount\n";

            if (isset($data['transactions'])) {
                foreach ($data['transactions'] as $transaction) {
                    $output .= $transaction['incurred_at'] . ",";
                    $output .= ucfirst($transaction['type']) . ",";
                    $output .= '"' . str_replace('"', '""', $transaction['description']) . '",';
                    $output .= "$" . number_format($transaction['amount'], 2, '.', '') . "\n";
                }
            }
        } elseif ($reportType === 'feed_inventory') {
            $output .= "Feed Inventory Report\n\n";
            $output .= "Feed Name,Quantity (kg),Unit Cost,Total Value,Last Updated\n";

            if (isset($data['inventory'])) {
                foreach ($data['inventory'] as $item) {
                    $totalValue = $item['quantity_kg'] * $item['unit_cost'];
                    $output .= $item['name'] . ",";
                    $output .= $item['quantity_kg'] . ",";
                    $output .= "$" . number_format($item['unit_cost'], 2, '.', '') . ",";
                    $output .= "$" . number_format($totalValue, 2, '.', '') . ",";
                    $output .= $item['last_updated'] . "\n";
                }
            }
        }

        return $output;
    }

    private function generateTextReport($data, $reportType, $startDate, $endDate) {
        $output = '';

        // Report header
        $output .= strtoupper(str_replace('_', ' ', $reportType)) . " REPORT\n";
        $output .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "Period: " . $startDate . " to " . $endDate . "\n";
        $output .= str_repeat("=", 50) . "\n\n";

        if ($reportType === 'batch_performance') {
            $output .= "BATCH PERFORMANCE DATA\n";
            $output .= str_repeat("-", 30) . "\n";

            if (isset($data['batches'])) {
                foreach ($data['batches'] as $batch) {
                    $output .= "Batch Code: " . $batch['batch_code'] . "\n";
                    $output .= "Breed: " . $batch['breed'] . "\n";
                    $output .= "Total Eggs: " . $batch['total_eggs'] . "\n";
                    $output .= "Avg Daily: " . round($batch['avg_daily']) . "\n";
                    $output .= "\n";
                }
            }

            $output .= "MORTALITY DATA\n";
            $output .= str_repeat("-", 20) . "\n";

            if (isset($data['mortality'])) {
                foreach ($data['mortality'] as $mortality) {
                    $output .= "Batch: " . $mortality['batch_code'] . " - Deaths: " . $mortality['total_deaths'] . "\n";
                }
            }
        } elseif ($reportType === 'financial') {
            $output .= "FINANCIAL SUMMARY\n";
            $output .= str_repeat("-", 20) . "\n";
            $output .= "Total Income: $" . number_format($data['summary']['total_income'], 2, '.', '') . "\n";
            $output .= "Total Expenses: $" . number_format($data['summary']['total_expenses'], 2, '.', '') . "\n";
            $output .= "Net Profit: $" . number_format($data['summary']['net_profit'], 2, '.', '') . "\n\n";

            $output .= "TRANSACTION DETAILS\n";
            $output .= str_repeat("-", 25) . "\n";

            if (isset($data['transactions'])) {
                foreach ($data['transactions'] as $transaction) {
                    $output .= date('M d, Y', strtotime($transaction['incurred_at'])) . " - ";
                    $output .= ucfirst($transaction['type']) . ": ";
                    $output .= $transaction['description'] . " - ";
                    $output .= "$" . number_format($transaction['amount'], 2, '.', '') . "\n";
                }
            }
        } elseif ($reportType === 'feed_inventory') {
            $output .= "FEED INVENTORY\n";
            $output .= str_repeat("-", 15) . "\n";

            if (isset($data['inventory'])) {
                foreach ($data['inventory'] as $item) {
                    $totalValue = $item['quantity_kg'] * $item['unit_cost'];
                    $output .= $item['name'] . "\n";
                    $output .= "  Quantity: " . $item['quantity_kg'] . " kg\n";
                    $output .= "  Unit Cost: $" . number_format($item['unit_cost'], 2, '.', '') . "\n";
                    $output .= "  Total Value: $" . number_format($totalValue, 2, '.', '') . "\n";
                    $output .= "  Last Updated: " . $item['last_updated'] . "\n\n";
                }
            }
        }

        $output .= "\n" . str_repeat("=", 50) . "\n";
        $output .= "Report generated by Poultry Management System\n";

        return $output;
    }
}
?>