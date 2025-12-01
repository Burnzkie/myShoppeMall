<?php
require_once '../config/Database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $category_id;
    public $image;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description, price=:price, stock=:stock, category_id=:category_id, image=:image, status='active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':image', $this->image);
        return $stmt->execute();
    }

    public function readAll($category_id = 0) {
        $query = "SELECT p.id, p.name, p.description, p.price, p.stock, p.image, c.name as category_name 
                  FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.status = 'active'";
        if ($category_id > 0) {
            $query .= " AND p.category_id = :category_id";
        }
        $query .= " ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if ($category_id > 0) {
            $stmt->bindParam(':category_id', $category_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->stock = $row['stock'];
            $this->category_id = $row['category_id'];
            $this->image = $row['image'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, stock=:stock, category_id=:category_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET status='inactive' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function uploadImage($file) {
        if ($file['error'] == 0) {
            $target_dir = "../../uploads/";
            $target_file = $target_dir . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    $this->image = basename($file["name"]);
                    return true;
                }
            }
        }
        return false;
    }
}
?>