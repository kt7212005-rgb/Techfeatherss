<?php
// models/UserModel.php
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addUser($email, $hash, $name, $role) {
        $stmt = $this->db->prepare('INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$email, $hash, $name, $role]);
    }

    public function updatePassword($userId, $hash) {
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([$hash, $userId]);
    }

    public function getUsers() {
        return $this->db->query('SELECT id, email, name, role, created_at FROM users ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>