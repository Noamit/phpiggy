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

try{

    $db->connection->beginTransaction();
    $db->connection->query("INSERT INTO products VALUES(99, 'Gloves')");

    $search = "Hats";
    $query = "SELECT * FROM products WHERE name=:name";
   
    $stmt = $db->connection->prepare($query);
    // $stmt->bindValue('name', $search, PDO::PARAM_STR);

    $stmt->bindValue('name', 'Gloves', PDO::PARAM_STR);

    $stmt->execute();
    var_dump($stmt->fetchAll(PDO::FETCH_OBJ));
    $db->connection->commit();
    
} catch (Exception $error) {
    if($db->connection->inTransaction()) {
        $db->connection->rollBack();
    }
    echo "Transaction faild!";
}