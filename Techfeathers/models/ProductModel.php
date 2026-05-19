<?php
// models/ProductModel.php
require_once __DIR__ . '/BaseModel.php';

class ProductModel extends BaseModel {
    public function getAllProducts() {
        return $this->db->query('SELECT * FROM products ORDER BY category, name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $description, $price, $category, $quantity) {
        $stmt = $this->db->prepare('INSERT INTO products (name, description, price, category, available_quantity) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$name, $description, $price, $category, $quantity]);
    }

    public function updateProduct($id, $name, $description, $price, $category, $quantity) {
        $stmt = $this->db->prepare('UPDATE products SET name = ?, description = ?, price = ?, category = ?, available_quantity = ? WHERE id = ?');
        return $stmt->execute([$name, $description, $price, $category, $quantity, $id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>