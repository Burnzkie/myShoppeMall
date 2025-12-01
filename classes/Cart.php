<?php
require_once '../config/Database.php';

class Cart {
    private $conn;
    private $table_name = "cart";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addItem($user_id, $product_id, $quantity = 1) {
        session_start();
        $session_id = session_id();
        $query = "INSERT INTO " . $this->table_name . " (user_id, session_id, product_id, quantity) VALUES (:user_id, :session_id, :product_id, :quantity) 
                  ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function getItems($user_id = null) {
        session_start();
        $session_id = session_id();
        $query = "SELECT c.*, p.name, p.price, p.image FROM " . $this->table_name . " c 
                  JOIN products p ON c.product_id = p.id 
                  WHERE (c.user_id = :user_id OR c.user_id IS NULL) AND c.session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuantity($cart_id, $quantity) {
        $query = "UPDATE " . $this->table_name . " SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $cart_id);
        return $stmt->execute();
    }

    public function removeItem($cart_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $cart_id);
        return $stmt->execute();
    }

    public function getTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function clearCart($user_id = null) {
        session_start();
        $session_id = session_id();
        $query = "DELETE FROM " . $this->table_name . " WHERE (user_id = :user_id OR user_id IS NULL) AND session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':session_id', $session_id);
        return $stmt->execute();
    }
}
?>