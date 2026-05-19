<?php
// models/OrderModel.php
require_once __DIR__ . '/BaseModel.php';

class OrderModel extends BaseModel {
    public function getProducts() {
        return $this->db->query('SELECT * FROM products WHERE available_quantity > 0 ORDER BY category, name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($productId) {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function decreaseProductQuantity($productId, $quantity) {
        $stmt = $this->db->prepare('UPDATE products SET available_quantity = available_quantity - ? WHERE id = ? AND available_quantity >= ?');
        return $stmt->execute([$quantity, $productId, $quantity]);
    }

    public function placeOrder($userId, $productId, $quantity) {
        $product = $this->getProductById($productId);
        if (!$product || $quantity <= 0 || $product['available_quantity'] < $quantity) {
            return false;
        }

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)');
            $orderInserted = $stmt->execute([$userId, $productId, $quantity]);
            $stockUpdated = $this->decreaseProductQuantity($productId, $quantity);

            if ($orderInserted && $stockUpdated) {
                $this->db->commit();
                return true;
            }

            $this->db->rollBack();
            return false;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function getAllOrders() {
        return $this->db->query('
            SELECT o.*, p.name as product_name, p.price, u.name as user_name, u.email as user_email
            FROM orders o
            JOIN products p ON p.id = o.product_id
            JOIN users u ON u.id = o.user_id
            ORDER BY o.order_date DESC
        ')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare('UPDATE orders SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $orderId]);
    }

    public function getUserOrders($userId) {
        $stmt = $this->db->prepare('
            SELECT o.*, p.name as product_name, p.price
            FROM orders o
            JOIN products p ON p.id = o.product_id
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>