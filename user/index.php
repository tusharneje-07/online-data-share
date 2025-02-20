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
            if($row['template'] == 'TEMP0'){
                $_SESSION['TEMP0_SESSION'] = $row;
                header('Location: ad55245e/?q='.$uid.'');
                exit();
            }
            if($row['template'] == 'TEMP1'){
                $_SESSION['TEMP0_SESSION'] = $row;
                header('Location: 345c75e4/?q='.$uid.'');
                exit();
            }
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
