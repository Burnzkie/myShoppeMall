<?php
require_once 'User.php';

class Staff extends User {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = 'staff';
    }

    public function getAllOrders() {
        $query = "SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>