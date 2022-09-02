<?php
// Include de database connectie
include $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

$id = $_POST['id'];

$data = [
    'id' => $id,
];

$sql = "DELETE FROM glrevents_activiteiten WHERE id = :id";
$stmt= $pdo->prepare($sql);
$stmt->execute($data);

header('Location: /admin/settings_activities.php');

?>