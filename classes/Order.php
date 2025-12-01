<?php
require_once '../config/Database.php';

class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $shipping_address;
    public $payment_method;
    public $payment_status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($items, $shipping_address, $payment_method) {
        $this->conn->beginTransaction();
        try {
            // Insert order
            $query = "INSERT INTO " . $this->table_name . " (user_id, total_amount, shipping_address, payment_method, payment_status) VALUES (:user_id, :total, :address, :method, 'paid')";
            $stmt = $this->conn->prepare($query);
            $total = 0;
            foreach ($items as $item) $total += $item['price'] * $item['quantity'];
            $this->total_amount = $total;
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':total', $this->total_amount);
            $stmt->bindParam(':address', $shipping_address);
            $stmt->bindParam(':method', $payment_method);
            $stmt->execute();
            $this->id = $this->conn->lastInsertId();

            // Insert order items and update stock
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :price)";
            $item_stmt = $this->conn->prepare($item_query);
            foreach ($items as $item) {
                $item_stmt->execute([
                    ':order_id' => $this->id,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);

                // Update stock
                $stock_query = "UPDATE products SET stock = stock - :qty WHERE id = :pid";
                $stock_stmt = $this->conn->prepare($stock_query);
                $stock_stmt->execute([':qty' => $item['quantity'], ':pid' => $item['product_id']]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function readByUser($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>