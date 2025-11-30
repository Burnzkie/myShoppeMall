<?php

require_once 'User.php';

class Admin extends User {
    public function __construct(){
        parent::__construct();
        $this->role = 'admin';
    }

    public function createStaff($name, $email, $password){
        return $this->register($name, $email, $password, 'staff');
    }

        public function getAllUsers(){
            $query = "SELECT id, role, status, created_at FROM users ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

            public function deleteProduct($productId){
                $query = "DELETE FROM products WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $productId);
                return $stmt->execute();
            }
}