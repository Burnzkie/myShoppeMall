<?php
require_once 'User.php';

class Admin extends User {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = 'admin';
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createStaff($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = 'staff';
        return $this->create();
    }
}
?>