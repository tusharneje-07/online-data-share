<?php
session_start();
require_once './db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: login");
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET['q'])){
        header("Location: login");
        exit();
    }

    $uid = $_GET['q'];
    echo $uid;

    $stmt = $pdo->prepare("SELECT * FROM user_information WHERE uid = ?");
    $stmt->execute([$uid]);
    var_dump($stmt->fetch());


    
} else {
    echo "This is a " . $_SERVER['REQUEST_METHOD'] . " request.";
}

?>
