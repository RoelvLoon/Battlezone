<?php
// Include de Microsoft Auth, Microsoft Graph en database connectie
require $_SERVER["DOCUMENT_ROOT"] . "/inc/auth.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/graph.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";

// Haal site-gegevens op uit database
$stmt = $pdo->query("SELECT * FROM glrevents_settings_event");
$siteData = $stmt->fetch();
$stmt = null;

// Check of er een event gaande is, zo niet, laad offline.php in en exit.
if ($siteData["offline"] == 1) {
    include $_SERVER["DOCUMENT_ROOT"] . '/components/offline.php';
    exit;
}

// Microsoft Authenticatie en Graph class aanmaken
$Auth = new modAuth();
$Graph = new modGraph();

// Profiel informatie ophalen
$photo = $Graph->getPhoto();
$profile = $Graph->getProfile();

if (isset($_POST["category"])) {
    
    $query = $pdo->prepare('SELECT * FROM glrevents_activiteiten WHERE type = ? AND status = "open"');
    $query->execute([$_POST["category"]]);
    $result = $query->fetchAll();

    if ($result) {
        foreach($result as $row) {
            $activiteitImg = $row['img'];
            ?>

            <div class="rounded my-5 mx-auto bg-background" style="max-width: 600px; overflow: hidden;">
                
                <div class='w-100' style="background-image: url('<?php 
                    if ($activiteitImg) {
                        echo $activiteitImg;
                    } else {
                        echo "/img/main/geenImg.jpeg"; //Dit kan een standaard image worden voor als er geen image in de database staat.
                    }
                ?>'); background-position: center; background-size: cover; background-repeat: no-repeat; height: 220px;">
                </div>
                <div class="d-md-flex justify-content-between py-3 px-5 bg-background flex-column">
                    <h3 class="my-auto"><?php echo ucfirst($row['naam']); ?></h3>
                    <p><?php echo $row['omschrijving']; ?></p>
                    <button type="button" class="mt-md-0 mt-3 my-md-0 btn btn-main activityButton" data-name="<?= ucfirst($row["naam"]) . ' (' . $profile->givenName . ')' ?>" data-id="<?= $row["id"] ?>" data-category="<?= $row["type"] ?>" data-bs-toggle="modal" data-bs-target="#activityModal"><div class="d-flex justify-content-center align-items-center"><i class="fs-2 fa-solid fa-qrcode me-3"></i>Toon QR Code</div></button>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<span>Er zijn geen activiteiten beschikbaar.</span>";
    }

} else {
    echo "<span>Er is iets misgegaan...</span>";
}