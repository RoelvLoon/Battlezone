<?php

require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

// Sidebar active class
include($_SERVER["DOCUMENT_ROOT"] . "/actions/set_active.php");

$stmt = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type = "event"');

$cateringChecker = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type ="catering"')->fetchAll();
$cateringCheck = count($cateringChecker);

$eventChecker = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type ="event"')->fetchAll();
$eventCheck = count($eventChecker);

$catering = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE category ="catering"')->fetchAll();
$totaalCatering = count($catering);

$event = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE category ="event"')->fetchAll();
$totaalEvent = count($event);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>

    <link rel="stylesheet" href="../lib/css/glrevents/circle-progress.css">
    <script type="text/javascript" src="../lib/js/glrevents/circle-progress.js" defer></script>

    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>

    <title>Overzicht | Adminpaneel</title>

    <link rel="stylesheet" href="../lib/css/glrevents/admin.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
        function myFunction() { 
            document.getElementById("mainFrameOne").style.display="none"; 
            document.getElementById("mainFrameTwo").style.display="block"; 
        }
    </script>
</head>
<body>

    <!-- Topbar -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">Overzicht</span>
            <div>
                <i class="fa-solid fa-arrow-left fa-fw fs-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></i>
            </div>
        </div>
    </nav>

    <div class="d-flex">

        <!-- Sidebar mobile -->
        <div class="d-block d-lg-none offcanvas offcanvas-start d-flex flex-column flex-shrink-0 p-3 text-white bg-dark vh-100" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" style="width: 80%;">
            <?php include("../components/admin_nav.php"); ?>
        </div>

        <!-- Sidebar desktop -->
        <div class="d-none d-lg-flex d-flex flex-column flex-shrink-0 p-3 text-white bg-dark vh-100 position-fixed bottom-0" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" style="width: 300px;">
            <?php include("../components/admin_nav.php"); ?>
        </div>

        <!-- Content -->
        <main class="p-4 w-100 admin-content text-center">
            <div class="d-none d-lg-block">
                <h1 class="text-center">Events</h1>
                <div class="h4 font-weight-bold mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal aangemelde mensen voor heel het event">
                    <span class="fs-2"><?php echo $totaalEvent; ?></span>
                </div>
                <span class="text-gray">Totaal aanmeldingen</span>
                <hr>
            </div>
            <div class="d-block d-lg-none">
                <h1 class="text-center">Events</h1>
                <div class="h4 font-weight-bold mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal aangemelde mensen voor heel het event">
                    <span class="fs-2"><?php echo $totaalEvent; ?></span>
                </div>
                <span class="text-gray">Totaal aanmeldingen</span>
                <hr>
            </div>
            <?php
            if($eventCheck == 0){

                echo
                '<div class="mt-5 pt-5">
                    <h2>Momenteel zijn er geen events...</h2>
                    <p>Als u een event wilt toevoegen, druk dan op de knop hieronder.</p>
                    <a href="https://events.sd-lab.nl/admin/settings_activities.php" class="btn btn-primary mt-2">Event aanmaken</a>
                </div>';

            }else{

            while ($row = $stmt->fetch()) {

                $query = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE type ="'.$row["naam"].'"');         
                $counting = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE type ="'.$row["naam"].'"')->fetchAll();
                $gebruiktCount = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE gebruikt = 1 AND type ="'.$row["naam"].'"')->fetchAll();
                $totaal = $pdo->query('SELECT * FROM glrevents_qrcodes')->fetchAll();

                $gebruiktCounter = count($gebruiktCount);

                $aantalMensen = count($counting);
                $totaalMensen = count($totaal);
                $percentageMensen = round(100 / $row["max"] * $aantalMensen,2);

                echo
                '<div class="row d-flex justify-content-center align-items-center">
                    <div class="col-xxl-10 mb-4">
                        <div class="bg-light rounded-lg p-5 shadow">
                            <h3 class=" font-weight-bold text-center mb-4">' .  ucfirst($row["naam"]) . '</h3>

                            <!-- circleProgress bar 1 -->
                            <div class="circleProgress mx-auto" data-value="' . $percentageMensen . '">
                                <span class="circleProgress-left">
                                    <span class="circleProgress-bar border-primary"></span>
                                </span>
                                <span class="circleProgress-right">
                                    <span class="circleProgress-bar border-primary"></span>
                                </span>
                                <div class="circleProgress-value w-100 h-100 bg-white rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold">' . $percentageMensen . '%</div>
                                </div>
                            </div>
                            <!-- END -->

                            <!-- Demo info -->
                            <div class="row text-center mt-4">
                                <div class="col-4 border-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Max aantal plaatsen voor een event">
                                    <div class="h4 font-weight-bold mb-0">' . $row["max"] . '</div><span class="small text-gray">Max</span>
                                </div>
                                <div class="col-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal ingegschreven mensen voor een event">
                                    <div class="h4 font-weight-bold mb-0">' . $aantalMensen . '</div><span class="small text-gray">Aangemeld</span>
                                </div>
                                <div class="col-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal mensen die naar het event zijn geweest">
                                    <div class="h4 font-weight-bold mb-0">' . $gebruiktCounter  . '</div><span class="small text-gray">Geweest</span>
                                </div>
                            </div>
                            <!-- END -->
                        </div>
                    </div>
                </div>';

            }
            }
            ?>

        </main>

        <div class="col-xxl-3 bg-light p-4 border rounded text-center infoContainer -vh-100 position-sticky top-0 start-0 overflow-auto d-none d-lg-block">
            <div>
                <h1 class="text-center">Catering</h1>
                <div class="h4 font-weight-bold mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal aangemelde mensen voor de catering">
                    <span class="fs-2"><?php echo $totaalCatering; ?></span>
                </div>
                <span class="text-gray">Totaal aanmeldingen</span>
                <hr>
            </div> 
            <div>
                <?php

                    if($cateringCheck == 0){

                        echo
                        '<div class="mt-5">
                            <h5>Er is momenteel niks voor de catering...</h5>
                            <p>Als u een consumptie wilt toevoegen, druk dan op de knop hieronder.</p>
                            <a href="https://events.sd-lab.nl/admin/settings_activities.php" class="btn btn-primary mt-2">Consumptie toevoegen</a>
                        </div>';
                        
                    } else{

                    //Totaal aantal event som

                    $procentEvent = round(100 / $totaalMensen * $totaalEvent,2);

                    // Totaal aantal catering som

                    $procentCatering = round(100 / $totaalMensen * $totaalCatering,2);


                    $cateringOverzicht = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type = "catering"');

                    while ($row = $cateringOverzicht->fetch()) {

                        $consumptieGebruikt = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE gebruikt = 1 AND type ="'.$row["naam"].'"')->fetchAll();
                        $consumptieGebruiktCheck = count($consumptieGebruikt);

                        $cateringCount = $pdo->query('SELECT COUNT(type) FROM glrevents_qrcodes WHERE type =  "'.$row["naam"].'"')->fetchColumn();

                        echo 
                        '<div class="row text-center mt-4 d-flex flex-row align-content-center">
                            <h5>' .ucfirst($row["naam"]). '</h5>

                            <div class="col-6 border-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal mensen die van plan zijn ' . $row['naam'] . ' te halen">
                                <div class="h4 font-weight-bold mb-0">
                                <span>' . $cateringCount . '</span>
                                </div>
                                <span class="small text-gray">Aangemeld</span>
                            </div>

                            <div class="col-6 border-right mb-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal mensen die ' . $row['naam'] . ' hebben gehaald">
                                <div class="h4 font-weight-bold mb-0">
                                <span>' . $consumptieGebruiktCheck . '</span>
                                </div>
                                <span class="small text-gray">Opgehaald</span>
                            </div>
                            <hr>
                        </div>';
                    }
                    echo
                    '</div>
                    <div class="text-center mt-5 text-muted">
                        <p>Download alle gegevens en statistieken</p>
                        <a href="https://events.sd-lab.nl/admin/settings_reset.php" class="btn btn-outline-secondary">Download</a>
                    </div>';
                    }


                    ?>
        </div>

        <!-- Mobile sidebar right -->

        <div class="col-xxl-3 bg-light p-4 border text-center infoContainer overflow-auto offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="d-flex flex=row align-items-center">
                    <h1 class="text-center fs-1 ps-5">Catering</h1>
                    <i class="fa-solid fa-arrow-right fa-fw fs-3 text-dark ms-4" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></i>
                </div>
                <div class="h4 font-weight-bold mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal aangemelde mensen voor de catering">
                    <span class="fs-2"><?php echo $totaalCatering; ?></span>
                </div>
                <span class="text-gray">Totaal aanmeldingen</span>
                  
            </div> 
            <hr>
            <div>
                
                <?php

                    if($cateringCheck == 0){

                        echo
                        '<div class="mt-5">
                            <h5>Er is momenteel niks voor de catering...</h5>
                            <p>Als u een consumptie wilt toevoegen, druk dan op de knop hieronder.</p>
                            <a href="https://events.sd-lab.nl/admin/settings_activities.php" class="btn btn-primary mt-2">Consumptie toevoegen</a>
                        </div>';
                        
                    } else{
                    // Totaal aantal event som

                    $event = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE category ="event"')->fetchAll();
                    $totaalEvent = count($event);

                    $procentEvent = round(100 / $totaalMensen * $totaalEvent,2);

                    // Totaal aantal catering som
                    $catering = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE category ="catering"')->fetchAll();
                    $totaalCatering = count($catering);


                    $procentCatering = round(100 / $totaalMensen * $totaalCatering,2);

                    $cateringOverzicht = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type = "catering"');

                    while ($row = $cateringOverzicht->fetch()) {

                        $consumptieGebruikt = $pdo->query('SELECT * FROM glrevents_qrcodes WHERE gebruikt = 1 AND type ="'.$row["naam"].'"')->fetchAll();
                        $consumptieGebruiktCheck = count($consumptieGebruikt);

                        $cateringCount = $pdo->query('SELECT COUNT(type) FROM glrevents_qrcodes WHERE type =  "'.$row["naam"].'"')->fetchColumn();

                        echo 
                        '<div class="row text-center mt-4 d-flex flex-row align-content-center">
                        <h5>' .ucfirst($row["naam"]). '</h5>

                        <div class="col-6 border-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal mensen die van plan zijn ' . $row['naam'] . ' te halen">
                            <div class="h4 font-weight-bold mb-0">
                            <span>' . $cateringCount . '</span>
                            </div>
                            <span class="small text-gray">Aangemeld</span>
                        </div>

                        <div class="col-6 border-right mb-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Totaal aantal mensen die ' . $row['naam'] . ' hebben gehaald">
                            <div class="h4 font-weight-bold mb-0">
                            <span>' . $consumptieGebruiktCheck . '</span>
                            </div>
                            <span class="small text-gray">Opgehaald</span>
                        </div>
                        <hr>
                    </div>';

                    
                         
                    }
                    echo
                    '</div>
                    <div class="text-center mt-5 text-muted">
                        <p>Download alle gegevens en statistieken</p>
                        <a href="https://events.sd-lab.nl/admin/settings_reset.php" class="btn btn-outline-secondary">Download</a>
                    </div>';
                }
                
                ?>
        </div>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

</body>
</html>