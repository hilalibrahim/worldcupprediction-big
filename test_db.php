<?php
require 'config/config.php';
require 'config/database.php';
$db = Database::getInstance();
$users = $db->resultSet("SELECT id, username, points FROM users");
print_r($users);
