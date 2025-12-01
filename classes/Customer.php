<?php
require_once 'User.php';

class Customer extends User {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = 'customer';
    }
}
?>