<?php
require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

$handler = $_POST['handler'];

if ($handler == "qrcode") {

    $checkbox = $_POST['checkbox'];
    $id = $_POST['id'];
    
    if ($checkbox == 'false') {
        $gebruikt = 0;
    } else {
        $gebruikt = 1;
    }
    
    $stmt = $pdo->prepare('UPDATE glrevents_qrcodes SET gebruikt=:gebruikt WHERE id = :id');
    $stmt->execute([':id' => $id, ':gebruikt' => $gebruikt]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    
    echo $gebruikt;
} else if ($handler == "account") {
    exit;
}



?>