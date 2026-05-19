<?php
// models/EggModel.php
require_once __DIR__ . '/BaseModel.php';

class EggModel extends BaseModel {
    public function getBatches() {
        return $this->db->query('SELECT id, batch_code FROM batches ORDER BY batch_code')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addEggRecord($batchId, $collectedAt, $quantity) {
        $stmt = $this->db->prepare('INSERT INTO eggs (batch_id, collected_at, quantity) VALUES (?, ?, ?)');
        return $stmt->execute([$batchId, $collectedAt, $quantity]);
    }

    public function getEggRecords() {
        return $this->db->query('SELECT e.id, e.collected_at, e.quantity, b.batch_code FROM eggs e JOIN batches b ON b.id = e.batch_id ORDER BY e.collected_at DESC, e.id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>