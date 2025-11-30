<?php

require_once '../config/Database.php';

class User {
    protected $db;
    protected $table = 'users';

    public $id;
    public $name;
    public $email;
    public $role;
    public $password;

    public function __constuct() {
        $this->db = (new Database())->connect();
    }

    public function register($name, $email, $password, $role = 'customers') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->table (name, email, password, role) VALUES (:name, :email, :password, :role)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    public function login($email, $password){
        $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

            if ($stmt->rowCount() > 0){
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    $this->id = $user['id'];
                    $this->name = $user['name'];
                    $this->email = $user['email'];
                    $this->role = $user['role'];
                    return true;
                }
            }
            return false;
    }

            public function isAdmin(){
                return $this->role === 'admin';
            }

            public function isStaff(){
                return $this->role === 'staff';
            }
}