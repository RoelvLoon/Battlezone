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
$status = $_POST["status"];
$naam = $_POST["naam"];
$beschrijving = $_POST["beschrijving"];
$type = $_POST["type"];

if (isset($_POST['submit'])) {
    $file = $_FILES['img'];

    $fileName = $_FILES['img']['name'];
    $fileTmpName = $_FILES['img']['tmp_name'];
    $fileSize = $_FILES['img']['size'];
    $fileError = $_FILES['img']['error'];
    $fileType = $_FILES['img']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array("jpg", "jpeg", "png", "webp", "gif");

    if (!$fileName) {
        echo 'geen naam';
        $data = [
            'naam' => $naam,
            'omschrijving' => $beschrijving,
            'type' => $type,
            'status' => $status,
        ];
        $sql = "UPDATE glrevents_activiteiten SET naam = :naam, omschrijving = :omschrijving, type = :type, status = :status WHERE id = $id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        
        // QR codes bijwerken
        $sql = "UPDATE glrevents_qrcodes SET type = ?, category = ? WHERE activity_id = ?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$naam, $type, $id]);

        header('Location: /admin/settings_activities.php');

    } else {

        if (in_array($fileActualExt, $allowed)) {
            if($fileError === 0) {
                if ($fileSize < 8192000) {
                        $fileNameNew = uniqid('', true).".".$fileActualExt;
                        $fileDestination = '../activiteiten/uploads/' . $fileNameNew; //stop '../uploads/'. voor de filenamenew en zet de filenamenew in de database tabel img
                        move_uploaded_file($fileTmpName, $fileDestination);
                        echo 'gelukt met het uploaden';
                        
                } else {
                    echo 'De file is te groot';
                }
            } else {
                echo 'Er was een error met het uploaden van de file';
            }
        } else {
            echo "U kunt dit soort fotos / files niet uploaden.";
        }

        $data = [
            'naam' => $naam,
            'img' => $fileDestination,
            'omschrijving' => $beschrijving,
            'type' => $type,
            'status' => $status,
        ];
        $sql = "UPDATE glrevents_activiteiten SET naam = :naam, img = :img, omschrijving = :omschrijving, type = :type, status = :status WHERE id = $id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);

        // QR codes bijwerken
        $sql = "UPDATE glrevents_qrcodes SET type = ?, category = ? WHERE activity_id = ?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$naam, $type, $id]);

        header('Location: /admin/settings_activities.php');

    }
}

?>