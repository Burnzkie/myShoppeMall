<?php
require_once '../config/Database.php';

abstract class User {
    protected $conn;
    protected $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $phone;
    public $address;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password, role=:role, phone=:phone, address=:address, status=:status";
        $stmt = $this->conn->prepare($query);

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->status = 'active';

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
        }
    }

    public static function authenticate($email, $password) {
        $database = new Database();
        $db = $database->getConnection();
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                return true;
            }
        }
        return false;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    public static function logout() {
        session_destroy();
    }
}
?>