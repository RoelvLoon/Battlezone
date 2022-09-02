<?php
// Include de Microsoft Auth, Microsoft Graph en database connectie
require $_SERVER["DOCUMENT_ROOT"] . "/inc/auth.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/graph.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";

// Include de Bootstrap 5 color generator
require $_SERVER["DOCUMENT_ROOT"] . "/actions/generate_bs5_color.php";

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

?>

<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
        <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>

        <title>Activiteiten | <?= $siteData["title"] ?></title>

        <meta content="Activiteiten | <?= $siteData["title"] ?>" property="og:title">
        <meta content="<?= $siteData["description"] ?>" property="og:description">
        <meta content="<?= $siteData["title"] ?>" property="og:site_name">
        <meta content='/img<?= $siteData["main_logo"] ?>' property='og:image'>

        <style>
            <?= $siteData["main_css"] ?>
            <?= $siteData["nav_css"] ?>
            <?= $siteData["background_css"] ?>
        </style>
    </head>
    <body class="min-vh-100 bg-background" style="background-color: <?= adjustBrightness($siteData["color_background"], -0.4) ?> !important;">

        <?php // Include de nav.php voor de navigatiebalk ?>
        <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/nav.php') ?>
        
        <div class="d-flex justify-content-center align-items-center my-5">
            <?php
                // Haal op uit de database wat voor activiteiten er zijn, en maak op basis daarvan de buttons aan op de pagina.
                $catering = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type = "catering" AND status = "open"')->fetchAll();
                $totaalCatering = count($catering);

                $event = $pdo->query('SELECT * FROM glrevents_activiteiten WHERE type = "event" AND status = "open"')->fetchAll();
                $totaalEvent = count($event);

                // Als er zowel events als catering zijn; beide buttons
                if ($totaalEvent > 0 && $totaalCatering > 0) { 
                    echo '<button class="btn btn-main mx-2 activityListButton" id="eventButton" data-id="event">Evenementen (' . $totaalEvent . ')</button>';
                    echo '<button class="btn btn-main mx-2 activityListButton" id="cateringButton" data-id="catering">Catering (' . $totaalCatering . ')</button>';
                } 

                // Als er alleen catering is maar geen event; catering button
                if ($totaalCatering > 0 && $totaalEvent < 1) { 
                    echo '<button class="btn btn-main mx-2 activityListButton" id="cateringButton" data-id="catering">Catering (' . $totaalCatering . ')</button>';
                } 
                
                // Als er alleen events zijn maar geen catering; event button
                if ($totaalEvent > 0 && $totaalCatering < 1) { 
                    echo '<button class="btn btn-main mx-2 activityListButton" id="eventButton" data-id="event">Evenementen (' . $totaalEvent . ')</button>';
                } 

                // Als beide er niet zijn; beide buttons, maar pagina toont 0 resultaten.
                if ($totaalEvent < 1 && $totaalCatering < 1) { 
                    echo '<button class="btn btn-main mx-2 activityListButton" id="eventButton" data-id="event">Evenementen (' . $totaalEvent . ')</button>';
                    echo '<button class="btn btn-main mx-2 activityListButton" id="cateringButton" data-id="catering">Catering (' . $totaalCatering . ')</button>';
                } 
            ?>
        </div>

        <div class="text-center w-75 mb-5 mx-auto" id="activityList"></div>

        <div class="modal fade text-dark" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activityModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ratio ratio-1x1 my-3" id="loadBox">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="spinner-border text-main" role="status">
                                    <span class="visually-hidden">Laden...</span>
                                </div>
                            </div>
                        </div>
                        <div class="ratio ratio-1x1 my-3" id="qrBox">
                            <img src="" id="activityQRcode" class="w-100 border border-1 shadow">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-main" data-bs-dismiss="modal">Sluiten</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>

        $(".activityListButton").click(function () {

            let category = $(this).attr('data-id');
            $(".activityListButton").removeClass("active");
            $(this).addClass("active");

            $("#activityList").html('<div class="spinner-border text-main" role="status"><span class="visually-hidden">Laden...</span></div>');

            $.ajax({
                type: "POST",
                url: "/actions/activity_get.php",
                data: {category: category},
                dataType: 'html',
                success: (response) => {
                    $("#activityList").html(response);
                }
            })
        })


        $(document).on("click", ".activityButton", function () {

            $("#qrBox").css("display", "none");
            $("#loadBox").css("display", "block");

            let name = $(this).attr('data-name');
            let id = $(this).attr('data-id');
            let category = $(this).attr('data-category');
            let url = "/actions/qrcode_get.php?category=" + category + "&type=" + id;

            $("#activityModalLabel").html(name);

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'html',
                success: (response) => {
                    let blob = new Blob([response], {type: 'image/svg+xml'});
                    let urlBlob = URL.createObjectURL(blob);
                    $("#activityQRcode").attr("src", urlBlob);
            
                    $("#qrBox").css("display", "block");
                    $("#loadBox").css("display", "none");
                }
            })
        })

        </script>
        
        <?php 
            if ($totaalEvent > 0) { 
                echo '<script>';
                echo '$("#eventButton").click();';
                echo '</script>';
            } 

            if ($totaalCatering > 0 && $totaalEvent < 1) { 
                echo '<script>';
                echo '$("#cateringButton").click();';
                echo '</script>';
            }

            if ($totaalCatering < 1 && $totaalEvent < 1) { 
                echo '<script>';
                echo '$("#eventButton").click();';
                echo '</script>';
            }
        ?>

    </body>
</html>