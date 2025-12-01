<?php
require_once '../config/Database.php';

class Category {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>