<?php
// models/ChickenModel.php
require_once __DIR__ . '/BaseModel.php';

class ChickenModel extends BaseModel {
    public function getAllBatches() {
        return $this->db->query('SELECT * FROM batches ORDER BY started_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredBatches($search = '', $status = '') {
        $sql = 'SELECT * FROM batches';
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(batch_code LIKE ? OR breed LIKE ?)';
            $likeTerm = '%' . $search . '%';
            $params[] = $likeTerm;
            $params[] = $likeTerm;
        }

        if ($status !== '') {
            $conditions[] = 'status = ?';
            $params[] = $status;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY started_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMortalityRecords() {
        return $this->db->query(
            'SELECT m.id, m.recorded_at, m.deaths, m.reason, m.notes, b.batch_code
             FROM mortality_records m
             JOIN batches b ON b.id = m.batch_id
             ORDER BY m.recorded_at DESC, m.id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBatch($code, $breed, $quantity, $startedAt) {
        $stmt = $this->db->prepare('INSERT INTO batches (batch_code, breed, started_at, quantity, status) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$code, $breed, $startedAt, $quantity, 'active']);
    }

    public function addMortalityRecord($batchId, $deaths, $reason, $notes, $recordedAt) {
        $stmt = $this->db->prepare('INSERT INTO mortality_records (batch_id, recorded_at, deaths, reason, notes) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$batchId, $recordedAt, $deaths, $reason, $notes]);
    }

    public function getTotalPopulation() {
        return (int) $this->db->query('SELECT IFNULL(SUM(quantity), 0) FROM batches')->fetchColumn();
    }

    public function getTotalBreeds() {
        return (int) $this->db->query('SELECT COUNT(DISTINCT breed) FROM batches')->fetchColumn();
    }

    public function getProductionReady() {
        return (int) $this->db->query("SELECT COUNT(*) FROM batches WHERE status = 'active'")->fetchColumn();
    }

    public function getAvgMortality() {
        // Compute average mortality as total deaths / total initial population * 100
        $totalDeaths = (int) $this->db->query('SELECT IFNULL(SUM(deaths), 0) FROM mortality_records')->fetchColumn();
        $totalPopulation = (int) $this->db->query('SELECT IFNULL(SUM(quantity), 0) FROM batches')->fetchColumn();

        if ($totalPopulation <= 0) {
            return '0.00';
        }

        $avg = ($totalDeaths / $totalPopulation) * 100;

        return number_format($avg, 2);
    }
}
?>