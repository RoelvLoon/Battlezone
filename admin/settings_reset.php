<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["perms"]["admin"])) {
    echo '<script>window.history.back();</script>';
    exit;
}

// Sidebar active class
include($_SERVER["DOCUMENT_ROOT"] . "/actions/set_active.php");

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>

    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
    
    <title>Reset | Adminpaneel</title>

    <link rel="stylesheet" href="../lib/css/glrevents/admin.css">

    <script src="/lib/js/glrevents/download.js" defer></script>
</head>
<body>

    <!-- Topbar -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">Reset</span>
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
        <main class="p-4 w-100 admin-content">
            <div class="d-none d-lg-block">
                <h1 class="text-center">Reset</h1>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadModal">Gegevens downloaden</button>

                        <!-- Modal -->
                        <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="downloadModalLabel">Gegevens downloaden</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Weet je zeker dat je de gegevens wilt downloaden?<br>
                                        Dit bevat afbeeldingen, instellingen en andere gegevens.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                        <form action="/actions/reset_download.php" method="POST">
                                            <button type="submit" class="btn btn-primary">Ja, downloaden!</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        </main>

    </div>
</body>
</html>
