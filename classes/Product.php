<?php

class Product {
    private $db;

    public function __construct(){
        $this->db = (new Database())->connect();
    }

            public function getAll($limit = 20){
                $query = "SELECT p.*, c.name as category_name
                FROM products p LEFT JOIN categories c ON p. category_id
                WHERE p.status = 'active' ORDER BY p.created_at DESC LIMIT :limit";

                $stmt = $stmt->db->prepare($query);
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

                    public function getById($id) {
                        $query = "SELECT * FROM products WHERE id = :id";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        return $stmt->fetch(PDO::FETC_ASSOC);
                    }

                    public function add($name, $desc, $price, $stock, $category_id, $image = null) {

                        $query = "INSERT INTO products (name, description, price, stock, category_id, image) VALUES (:name, :desc, :price, :stock, :cat_id, :image)";
                        $stmt = $this->db->prepare($query);

                        $stmt->bindParam(':name', $this->name);
                        $stmt->bindParam(':desc', $desc);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':stock', $stock);
                        $stmt->bindParam(':cat_id', $category_id);
                        $stmt->bindParam(':image', $image);

                        return $stmt->execute();
                    }
}