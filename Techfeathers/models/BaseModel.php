<?php
// models/BaseModel.php
require_once __DIR__ . '/../config.php';

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = get_db();
    }
}
?>