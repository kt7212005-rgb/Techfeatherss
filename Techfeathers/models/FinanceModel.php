<?php
// models/FinanceModel.php
require_once __DIR__ . '/BaseModel.php';

class FinanceModel extends BaseModel {
    public function addTransaction($type, $description, $amount, $incurredAt) {
        $stmt = $this->db->prepare('INSERT INTO finances (type, description, amount, incurred_at) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$type, $description, $amount, $incurredAt]);
    }

    public function getTransactions() {
        return $this->db->query('SELECT * FROM finances ORDER BY incurred_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>