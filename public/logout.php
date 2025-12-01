<?php
require_once '../classes/User.php';
User::logout();
header('Location: index.php');
exit();
?>