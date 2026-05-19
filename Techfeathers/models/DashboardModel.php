<?php
// models/DashboardModel.php
require_once __DIR__ . '/BaseModel.php';

class DashboardModel extends BaseModel {
    public function getTotalChickens() {
        return (int) $this->queryScalar('SELECT IFNULL(SUM(quantity), 0) FROM batches');
    }

    public function getDailyEggs($date) {
        return (int) $this->queryScalar('SELECT IFNULL(SUM(quantity), 0) FROM eggs WHERE collected_at = ?', [$date]);
    }

    public function getFeedInventoryKg() {
        return (float) $this->queryScalar('SELECT IFNULL(SUM(quantity_kg), 0) FROM feed_inventory');
    }

    public function getMonthlyNetProfit($month) {
        if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
            $sales = (float) $this->queryScalar("SELECT IFNULL(SUM(amount), 0) FROM finances WHERE type = 'sale' AND DATE_FORMAT(incurred_at, '%Y-%m') = ?", [$month]);
            $expenses = (float) $this->queryScalar("SELECT IFNULL(SUM(amount), 0) FROM finances WHERE type = 'expense' AND DATE_FORMAT(incurred_at, '%Y-%m') = ?", [$month]);
        } else {
            $sales = (float) $this->queryScalar("SELECT IFNULL(SUM(amount), 0) FROM finances WHERE type = 'sale' AND strftime('%Y-%m', incurred_at) = ?", [$month]);
            $expenses = (float) $this->queryScalar("SELECT IFNULL(SUM(amount), 0) FROM finances WHERE type = 'expense' AND strftime('%Y-%m', incurred_at) = ?", [$month]);
        }
        return $sales - $expenses;
    }

    public function getActiveBatches() {
        return $this->db->query('SELECT id, batch_code, breed, started_at, quantity, status FROM batches ORDER BY started_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEggProductionTrend($days = 7) {
        $labels = [];
        $eggData = [];
        $start = new DateTime('-' . ($days - 1) . ' days');
        for ($i = 0; $i < $days; $i++) {
            $date = $start->format('Y-m-d');
            $weekday = $start->format('D');

            $labels[] = $weekday;
            $eggData[] = $this->getDailyEggs($date);

            $start->modify('+1 day');
        }
        return ['labels' => $labels, 'data' => $eggData];
    }

    private function queryScalar($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
?>