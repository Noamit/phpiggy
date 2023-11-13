<?php

include __DIR__ . '/src/Framework/Database.php';

use Framework\Database;

$driver = 'mysql';
$config = [
    'host' => '127.0.0.1',
    'port' => 8080,
    'dbname' => 'phpiggy',
];
$username = 'root';
$password = '';
$db = new Database($driver, $config, $username, $password);

$sqlFile = file_get_contents("./database.sql");
$db->query($sqlFile);