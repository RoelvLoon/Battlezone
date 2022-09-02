<?php

require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";
include('../inc/phpqrcode/qrlib.php');

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["perms"]["qr-code"]) && empty($_SESSION["perms"]["admin"])) {
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

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <title>QR Codes | Adminpaneel</title>

    <link rel="stylesheet" href="/lib/css/glrevents/admin.css">
</head>
<body>

    <!-- Topbar -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">QR Codes</span>
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
                <h1 class="text-center">QR Codes</h1>
                <hr>
            </div>
           
                <?php
                
                // Attempt select query execution
                // $sql = "SELECT * FROM glrevents_admin";
                $query = $pdo->prepare("SELECT * FROM glrevents_qrcodes");
                $query->execute();

                $result = $query->fetchAll();
                
                if($result){
                    if(isset($result)){
                        echo '<table id="example" class="table table-bordered table-striped" style="width: 100%;">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Naam</th>";
                                    echo "<th>Functie</th>";
                                    echo "<th>Email</th>";
                                    echo "<th>Afdeling</th>";
                                    echo "<th>Gebruikt</th>";
                                    echo "<th>Categorie</th>";
                                    echo "<th>Type</th>";
                                    echo "<th>Acties</th>";
                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            foreach($result as $row){
                                $gebruikt = $row['gebruikt'];
                                if ($gebruikt == 0) {
                                    $gebruikTekst = "";
                                } else {
                                    $gebruikTekst = "checked";
                                }
                                echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['naam'] . "</td>";
                                    echo "<td>" . $row['functie'] . "</td>";
                                    echo "<td><a class='text-decoration-none' href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a></td>";
                                    echo "<td>" . $row['afdeling'] . "</td>";
                                    echo "<td> <input data-id='" . $row['id'] . "' class='checkGebruikt' type='checkbox'". $gebruikTekst . "></td>";
                                    echo "<td>" . ucfirst($row['category']) . "</td>";
                                    echo "<td>" . ucfirst($row['type']) . "</td>";
                                    echo "<td>";
                                        // echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                        // echo '<a class="me-3" title="Update Item" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                        echo '<a data-bs-toggle="modal" data-bs-target="#verwijderModal" data-id="' . $row['id'] . '" class="me-3 verwijderItem" title="Verwijder Item" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo '<a id="weergeefSubmit" title="Qr-code weergeven" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#testModel" data-id=' . $row['code'] . '><span class="fa fa-eye"></span></a>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";                
                        echo "</table>";
                        // Free result set
                        // unset($result);
                    } else{
                        echo '<div class="alert alert-danger"><em>Geen items gevonden...</em></div>';
                    }
                } else{
                    echo '<div class="alert alert-danger"><em>Geen items gevonden...</em></div>';
                }
                
                // Close connection
                // unset($pdo);
                ?>
    
        
        </main>

    </div>

     <!-- Modal verwijderen -->
     <div class="modal fade" id="verwijderModal" tabindex="-1" aria-labelledby="qrcodeModalLabel" data-bs-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qrcodeModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-0 text-center text-danger">LET OP: Na het verwijderen is het niet meer herstelbaar!</p>
                            <div class="my-3">
                                <!-- Misschien later hiero nog info -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-main btn-warning" id="confirm" data-id="ja">Ik weet het zeker!</button>
                            <button type="button" class="btn btn-danger btn-main" data-bs-dismiss="modal">Sluiten</button>
                        </div>
                    </div>
                </div>
            </div>
</body>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        scrollX: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.0/i18n/nl-NL.json',
        }
    });
    $('[data-toggle="tooltip"]').tooltip(); 

    // $("#weergeefSubmit").click(function (e) {
    //     e.preventDefault()
    //     $.ajax({
    //         type: "POST",
    //         url: "../actions/get_adminQR_code.php",
    //         data: {code: code},
    //         success: function (feedback) {
    //             feedbackImg = $.parseXML(feedback)
    //             $('#modalImg').attr('src', feedbackImg)
    //             // console.log(feedback)
    //             // $('#qrcodeModalLabel').text(feedback)


    //             // $("#message").html(feedback)
    //             // $("#message").addClass("text-danger")
    //         }
    //     })
        
    // })

    $(".checkGebruikt").click(function (e) {
        e.preventDefault()
        let checkbox = $(this).is(':checked');
        let id = $(this).attr('data-id');
        let qrcode = "qrcode";
        $.ajax({
            type: "POST",
            url: "/actions/qrcode_update.php",
            data: {handler: qrcode, checkbox: checkbox, id: id},
            success: (feedback) => {
                console.log(feedback)
                if (feedback == 0) {
                    $(this).prop('checked', false)
                } else {
                    $(this).prop('checked', true)
                }
            }
        })
        
    })

    $(".verwijderItem").click(function (e) {
        e.preventDefault()
        let id = $(this).attr('data-id');
        let confirm = $('#confirm').attr('data-id');
        // let naam = $('.getNaam').attr('data-id');
        $('.modal-title').html('Weet je zeker dat je ' + id + ' wilt verwijderen?')

        $("#confirm").off('click').click(function () {
            $.ajax({
            type: "POST",
            url: "/actions/qrcode_delete.php",
            data: {id: id, confirm: confirm},
            success: function (feedback)  {
                $('#verwijderModal').modal('hide');
                console.log(feedback)
                location.reload()

            }
        })
    })
    })



    // $('#testModel').on('show.bs.modal', function (e) {
    // var myRoomNumber = $(e.relatedTarget).attr('data-id');
    // $('#modalImg').attr('src', "../actions/get_adminQR_code.php?code=" + myRoomNumber)
    // $(this).find('.modal-title').text(myRoomNumber);
// });
} );

</script>
<style>
    /* .wrapper{
        width: 100%;
        margin: 0 auto;
    }
    table tr td:last-child{
        width: 120px;
    } */

    #example_filter{
        margin-bottom:  20px;
    }

    .form-select{
        width: 100px;
    }


</style>
</html>