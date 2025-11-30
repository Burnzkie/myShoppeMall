<?php

class Order {
    private $db;

    public function __construct(){
        $this->db = (new Database())->connect();
    }
        public function create($userId, $total, $address, $items){
            $this->db->beginTransaction();
            try {
                $query = "INSERT INTO orders (user_id. total_amount, shipping_address) 
                VALUES (:uid, :total, :addr)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':uid' => $userId,
                    ':total' => $total,
                    ':addr' => $address
                ]);
                $orderId = $this->db->lastInsertedId();

                $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, unit_price)\
                VALUES (:oid, :pid, :qty, :price";
                
                $itemStmt = $this->db->prepare($itemQuery);

                        foreach ($items as $item){
                            $itemStmt->execute([
                                ':oid' => $orderId,
                                ':pid' => $item['product_id'],
                                ':qty' => $item['quantity'],
                                ':price' => $item['price']
                            ]);
                        }
                        $this->db->commit();
                        return $orderId;
            } catch (Exception $e){
                $this->db->rollBack();
                return false;
            }
        }

        public function getUsesOrders($userId){
            $query = "SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':uid', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}