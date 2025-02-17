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

    $stmt = $pdo->prepare("SELECT * FROM user_information WHERE uid = ?");
    $stmt->execute([$uid]);
    $row = $stmt->fetch();
    if(!$row){
        echo "User Not Found";
        exit();
    }
    if (count($row) <= 0) {
        echo "User Not Found!";
        exit();
    }
    if($row['linkshare']){
        if($row['maritalstatus'] != 'MARRIED'){
            echo "Public Profile<br><br>";
            var_dump($row);
        }
        else{
            echo "Private Profile";
        }
    }
    else{
        echo "Private Profile";
    }
    


    
} else {
    echo "This is a " . $_SERVER['REQUEST_METHOD'] . " request.";
}

?>
