<?php
exec('mysql -uroot -p < config/add_user.sql');

try {
    include_once 'pdo.php';
    
    if ($sql = file_get_contents('config/dump.sql')) {
        $pdo->exec($sql);
        echo "Database created successfully\n";
    }
} catch (PDOException $e) {
    die('Error creating database: ' . $e->getMessage() . "\n");
}
