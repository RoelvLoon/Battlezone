<?php

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["perms"]["scanner"]) && empty($_SESSION["perms"]["admin"])) {
    echo '<script>window.history.back();</script>';
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");

// Sidebar active class
include($_SERVER["DOCUMENT_ROOT"] . "/actions/set_active.php");

$aantalEvents = $pdo->query('SELECT COUNT(type) FROM glrevents_activiteiten WHERE type = "event"')->fetchColumn();

$aantalCatering = $pdo->query('SELECT COUNT(type) FROM glrevents_activiteiten WHERE type = "catering"')->fetchColumn();

?>

<html class="h-100">
<head>
    <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>

    <title>Scanner | Adminpaneel</title>
    <script src="../lib/js/jsqr/jsQR.js"></script>
    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Ropa+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../lib/css/glrevents/scan.css">
    <script type="text/javascript" src="../lib/js/glrevents/scan.js" defer></script>
    <title>Scanner</title>

    <link rel="stylesheet" href="../lib/css/glrevents/admin.css">
</head>
<body class="bg-dark">
    

    <!-- Topbar mobile -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">Scanner</span>
            <div></div>
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
        <main class="w-100 scanFont admin-content">

            <?php 
                if ($aantalEvents > 0 && $aantalCatering > 0) {
            ?>

            <div class="position-fixed h-100 top-0 left-0 w-100 bg-dark justify-content-center align-items-center flex-column" id="welcome" style="display: flex;">
                <h1 class="text-white">Waarvoor scan je?</h1>
                <div>
                    <button id="event" class="btn btn-primary">Event</button>
                    <button id="catering" class="btn btn-primary">Catering</button>
                </div>
            </div>

            <?php
                } else if ($aantalEvents > 0 && $aantalCatering < 1) {
            ?>

            <div class="position-fixed h-100 top-0 left-0 w-100 bg-dark justify-content-center align-items-center flex-column" id="welcome" style="display: flex;">
                <h1 class="text-white">Welkom bij de scanner.</h1>
                <div>
                    <button id="event" class="btn btn-primary">Starten</button>
                </div>
            </div>

            <?php
                } else if ($aantalCatering > 0 && $aantalEvents < 1) {
            ?>

            <div class="position-fixed h-100 top-0 left-0 w-100 bg-dark justify-content-center align-items-center flex-column" id="welcome" style="display: flex;">
                <h1 class="text-white">Welkom bij de scanner.</h1>
                <div>
                    <button id="catering" class="btn btn-primary">Starten</button>
                </div>
            </div>

            <?php
                }
            ?>

            <div class="d-flex justify-content-center align-items-center scanner">
                <div class="bg-dark text-light justify-content-center align-items-center" id="loadingMessage">
                    🎥 Toegang tot camera afgewezen (zorg er voor dat u toestemming geeft)
                </div>

                <canvas class="rounded" id="canvas" hidden></canvas>
            </div>

            <div class="position-fixed h-100 top-0 left-0 w-100" id="output" style="display: none;"></div>
        </main>

    </div>

    <script>
        window.addEventListener('resize', () => {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        });
    </script>

</body>
</html>
