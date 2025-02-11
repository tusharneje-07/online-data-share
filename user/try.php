<?php
require_once './db.php';

// Example query
$stmt = $pdo->query("SELECT COUNT(*) FROM user_accounts");

while ($row = $stmt->fetch()) {
    echo $row['COUNT(*)'];
    // var_dump($row);
}
