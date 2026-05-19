<?php
// models/FeedModel.php
require_once __DIR__ . '/BaseModel.php';

class FeedModel extends BaseModel {
    public function addFeed($name, $quantity, $unitCost) {
        $stmt = $this->db->prepare('INSERT INTO feed_inventory (name, quantity_kg, unit_cost, last_updated) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$name, $quantity, $unitCost, date('Y-m-d')]);
    }

    public function getFeeds() {
        return $this->db->query('SELECT * FROM feed_inventory ORDER BY last_updated DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>