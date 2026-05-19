<?php
// models/ReportModel.php
require_once __DIR__ . '/BaseModel.php';

class ReportModel extends BaseModel {
    public function getMonthlyEggSummary() {
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            return $this->db->query("SELECT DATE_FORMAT(collected_at, '%Y-%m') AS month, SUM(quantity) AS total_eggs FROM eggs GROUP BY month ORDER BY month DESC")->fetchAll(PDO::FETCH_ASSOC);
        }
        return $this->db->query("SELECT strftime('%Y-%m', collected_at) AS month, SUM(quantity) AS total_eggs FROM eggs GROUP BY month ORDER BY month DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalEggsForMonth($month) {
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $stmt = $this->db->prepare("SELECT IFNULL(SUM(quantity),0) FROM eggs WHERE DATE_FORMAT(collected_at, '%Y-%m') = ?");
        } else {
            $stmt = $this->db->prepare("SELECT IFNULL(SUM(quantity),0) FROM eggs WHERE strftime('%Y-%m', collected_at) = ?");
        }
        $stmt->execute([$month]);
        return (int) $stmt->fetchColumn();
    }

    public function getWeeklyEggTrend($weeks = 7) {
        $trendLabels = [];
        $trendValues = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $week = (new DateTime())->modify("-{$i} weeks");
            $label = 'Week ' . $week->format('W');
            $weekKey = $week->format('o-W');

            $trendLabels[] = $label;
            if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(quantity),0) FROM eggs WHERE DATE_FORMAT(collected_at, '%x-W%v') = ?");
            } else {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(quantity),0) FROM eggs WHERE strftime('%Y-W', collected_at) = ?");
            }
            $stmt->execute([$weekKey]);
            $trendValues[] = (int) $stmt->fetchColumn();
        }

        return ['labels' => $trendLabels, 'values' => $trendValues];
    }

    public function getFeedConversionRatio($month = null) {
        // Feed conversion ratio = total feed consumed / total eggs produced
        // For simplicity, we'll calculate based on current feed inventory changes
        // In a real system, this would track feed consumption per batch

        if ($month === null) {
            $month = date('Y-m');
        }

        // Get total eggs for the month
        $totalEggs = $this->getTotalEggsForMonth($month);

        // Get feed consumption (simplified - using feed inventory changes)
        // This is a placeholder calculation
        $stmt = $this->db->prepare("SELECT SUM(quantity_kg) FROM feed_inventory");
        $totalFeed = (float) $stmt->fetchColumn();

        if ($totalEggs > 0 && $totalFeed > 0) {
            return round($totalFeed / $totalEggs, 2);
        }

        return 0.0;
    }

    public function getAverageMortalityRate($month = null) {
        if ($month === null) {
            $month = date('Y-m');
        }

        // Get total deaths for the month
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $stmt = $this->db->prepare("SELECT IFNULL(SUM(deaths),0) FROM mortality_records WHERE DATE_FORMAT(recorded_at, '%Y-%m') = ?");
        } else {
            $stmt = $this->db->prepare("SELECT IFNULL(SUM(deaths),0) FROM mortality_records WHERE strftime('%Y-%m', recorded_at) = ?");
        }
        $stmt->execute([$month]);
        $totalDeaths = (int) $stmt->fetchColumn();

        // Get total chickens (simplified - using batch quantities)
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(quantity),0) FROM batches WHERE status = 'active'");
        $totalChickens = (int) $stmt->fetchColumn();

        if ($totalChickens > 0) {
            return round(($totalDeaths / $totalChickens) * 100, 1);
        }

        return 0.0;
    }

    public function getFinancialTrend($weeks = 7) {
        $trendLabels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $week = (new DateTime())->modify("-{$i} weeks");
            $label = 'Week ' . $week->format('W');
            $weekStart = $week->format('Y-m-d');
            $weekEnd = $week->modify('+6 days')->format('Y-m-d');

            $trendLabels[] = $label;

            // Get income (sales) for the week
            if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(amount),0) FROM finances WHERE type = 'sale' AND incurred_at BETWEEN ? AND ?");
            } else {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(amount),0) FROM finances WHERE type = 'sale' AND incurred_at BETWEEN ? AND ?");
            }
            $stmt->execute([$weekStart, $weekEnd]);
            $incomeData[] = (float) $stmt->fetchColumn();

            // Get expenses for the week
            if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(amount),0) FROM finances WHERE type = 'expense' AND incurred_at BETWEEN ? AND ?");
            } else {
                $stmt = $this->db->prepare("SELECT IFNULL(SUM(amount),0) FROM finances WHERE type = 'expense' AND incurred_at BETWEEN ? AND ?");
            }
            $stmt->execute([$weekStart, $weekEnd]);
            $expenseData[] = (float) $stmt->fetchColumn();
        }

        return [
            'labels' => $trendLabels,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }

    public function getMonthlyFinancialSummary() {
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $sql = "SELECT 
                DATE_FORMAT(incurred_at, '%Y-%m') AS month,
                SUM(CASE WHEN type = 'sale' THEN amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses,
                SUM(CASE WHEN type = 'sale' THEN amount ELSE -amount END) AS net_profit
                FROM finances 
                GROUP BY month 
                ORDER BY month DESC";
        } else {
            $sql = "SELECT 
                strftime('%Y-%m', incurred_at) AS month,
                SUM(CASE WHEN type = 'sale' THEN amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expenses,
                SUM(CASE WHEN type = 'sale' THEN amount ELSE -amount END) AS net_profit
                FROM finances 
                GROUP BY month 
                ORDER BY month DESC";
        }

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateReport($type, $startDate, $endDate, $format = 'data') {
        $data = [];

        switch ($type) {
            case 'batch_performance':
                $data = $this->getBatchPerformanceReport($startDate, $endDate);
                break;
            case 'financial':
                $data = $this->getFinancialReport($startDate, $endDate);
                break;
            case 'feed_inventory':
                $data = $this->getFeedReport($startDate, $endDate);
                break;
            default:
                $data = ['error' => 'Unknown report type'];
        }

        if ($format === 'json') {
            return json_encode($data);
        }

        return $data;
    }

    private function getBatchPerformanceReport($startDate, $endDate) {
        // Get egg production data
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $stmt = $this->db->prepare("SELECT b.batch_code, b.breed, SUM(e.quantity) as total_eggs, AVG(e.quantity) as avg_daily
                FROM batches b 
                LEFT JOIN eggs e ON b.id = e.batch_id AND e.collected_at BETWEEN ? AND ?
                WHERE b.status = 'active'
                GROUP BY b.id, b.batch_code, b.breed");
        } else {
            $stmt = $this->db->prepare("SELECT b.batch_code, b.breed, SUM(e.quantity) as total_eggs, AVG(e.quantity) as avg_daily
                FROM batches b 
                LEFT JOIN eggs e ON b.id = e.batch_id AND e.collected_at BETWEEN ? AND ?
                WHERE b.status = 'active'
                GROUP BY b.id, b.batch_code, b.breed");
        }
        $stmt->execute([$startDate, $endDate]);
        $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get mortality data
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $stmt = $this->db->prepare("SELECT b.batch_code, SUM(m.deaths) as total_deaths
                FROM batches b 
                LEFT JOIN mortality_records m ON b.id = m.batch_id AND m.recorded_at BETWEEN ? AND ?
                GROUP BY b.id, b.batch_code");
        } else {
            $stmt = $this->db->prepare("SELECT b.batch_code, SUM(m.deaths) as total_deaths
                FROM batches b 
                LEFT JOIN mortality_records m ON b.id = m.batch_id AND m.recorded_at BETWEEN ? AND ?
                GROUP BY b.id, b.batch_code");
        }
        $stmt->execute([$startDate, $endDate]);
        $mortality = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'batches' => $batches,
            'mortality' => $mortality,
            'period' => ['start' => $startDate, 'end' => $endDate]
        ];
    }

    private function getFinancialReport($startDate, $endDate) {
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $stmt = $this->db->prepare("SELECT type, description, amount, incurred_at 
                FROM finances 
                WHERE incurred_at BETWEEN ? AND ? 
                ORDER BY incurred_at DESC");
        } else {
            $stmt = $this->db->prepare("SELECT type, description, amount, incurred_at 
                FROM finances 
                WHERE incurred_at BETWEEN ? AND ? 
                ORDER BY incurred_at DESC");
        }
        $stmt->execute([$startDate, $endDate]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalIncome = 0;
        $totalExpenses = 0;

        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'sale') {
                $totalIncome += $transaction['amount'];
            } else {
                $totalExpenses += $transaction['amount'];
            }
        }

        return [
            'transactions' => $transactions,
            'summary' => [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_profit' => $totalIncome - $totalExpenses
            ],
            'period' => ['start' => $startDate, 'end' => $endDate]
        ];
    }

    private function getFeedReport($startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT name, quantity_kg, unit_cost, last_updated 
            FROM feed_inventory 
            ORDER BY last_updated DESC");
        $stmt->execute();
        $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'inventory' => $inventory,
            'period' => ['start' => $startDate, 'end' => $endDate]
        ];
    }
}
?>