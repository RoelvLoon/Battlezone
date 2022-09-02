<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Te doen:
// Activiteit verwijderen
// Forms beveiligen met JQUERY (Robin)

// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["perms"]["activiteit"]) && empty($_SESSION["perms"]["admin"])) {
    echo '<script>window.history.back();</script>';
    exit;
}

// Sidebar active class
include($_SERVER["DOCUMENT_ROOT"] . "/actions/set_active.php");

require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";

$catering = $pdo->prepare("SELECT * FROM glrevents_activiteiten WHERE type = 'catering'");
$catering->execute();
$cateringResult = $catering->fetchAll();

$event = $pdo->prepare("SELECT * FROM glrevents_activiteiten WHERE type = 'event'");
$event->execute();
$eventResult = $event->fetchAll();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>

    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
    
    <title>Activiteiten | Adminpaneel</title>

    <link rel="stylesheet" href="/lib/css/glrevents/admin.css">
</head>
<body>

    <!-- Topbar -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">Activiteiten</span>
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
                <h1 class="text-center">Activiteiten</h1>
                <hr>
            </div>
            
            <div class="text-center">
                <button type="button" class="my-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActivityModal">+ Voeg een activiteit toe</button>
            </div>

            <!-- addActivityModal -->
            <div class="modal fade text-start" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addActivityModalLabel">Activiteit toevoegen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/actions/activity_create.php" enctype="multipart/form-data" method="POST">

                                <div class="form-group mb-3">
                                    <label for="activiteitStatus">Status</label>
                                    <select name="status" class="form-select">
                                        <option selected value="open">✅ Geopend</option>
                                        <option value="gesloten">❌ Gesloten</option>
                                    </select>
                                    <small class="form-text text-muted"><span class="text-danger">*</span> De activiteit wordt automatisch op <span class="text-success">geopend</span> ingesteld. Om de activiteit nog privé te houden kunt u hem beter op <span class="text-danger">gesloten</span> zetten. U kunt dit altijd nog veranderen.</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="d-block my-auto" for="activiteitNaam">Type</label>
                                    <select name="type" class="form-select">
                                        <option selected value="event">Evenement</option>
                                        <option value="catering">Catering</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="activiteitNaam">Naam</label>
                                    <input name="naam" type="text" class="form-control" aria-describedby="activiteitNaam" placeholder="Vul de naam in van de activiteit">
                                    <small class="form-text text-muted"><span class="text-danger">*</span> Dit veld is verplicht</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="activiteitBeschrijving">Beschrijving</label>
                                    <input name="beschrijving" type="text" class="form-control" aria-describedby="activiteitBeschrijving" placeholder="Vul de beschrijving in van de activiteit">
                                </div>


                                <div class="form-group mb-3">
                                    <label class="form-label" for="customFile">Afbeelding</label>
                                    <input name="img" type="file" class="form-control" id="customFile" />
                                </div>
                                
                                <div class="modal-footer p-0 pt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                    <button name="submit" type="submit" class="btn btn-primary">Toevoegen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">

                <div class="col-sm-12 col-md-6">

                    <div class="card my-3">
                        <div class="card-header">
                            Evenementen
                        </div>
                        <div class="card-body">

                            <div class="mx-auto text-center">
                                
                                <?php
                                
                                if ($eventResult) {
                                    foreach($eventResult as $row) {
                                        $activiteitImg = $row['img'];
                                ?>

                                    <div class="rounded bg-light mb-4 mx-auto border" style="max-width: 680px; overflow: hidden;">
                                    
                                        <div class='w-100' style="background-image: url('<?php 
                                            if ($activiteitImg) {
                                                echo $activiteitImg;
                                            } else {
                                                echo "/img/main/geenImg.jpeg"; //Dit kan een standaard image worden voor als er geen image in de database staat.
                                            }
                                        ?>'); background-position: center; background-size: cover; background-repeat: no-repeat; height: 220px;">
                                        </div>
                                        <div class="d-md-flex justify-content-between py-3 px-5 bg-light">
                                            <h3 class="my-auto"><?php echo $row['naam']; ?></h3>


                                            <!-- Manage modal -->
                                            <div class="modal fade" id="Toggle<?php echo $row['id'] ?>" aria-hidden="true" aria-labelledby="ToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ToggleLabel">Beheren <?php echo lcfirst($row['naam']) ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                            <div style="height: 100px; width: 100px; background-image: url('<?php 
                                                                if ($activiteitImg) {
                                                                    echo $activiteitImg;
                                                                } else {
                                                                    echo "/img/main/geenImg.jpeg";
                                                                }
                                                            ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;" class="bg-primary border rounded-circle my-3 mx-auto"></div>

                                                            <?php echo $row['omschrijving']; ?>

                                                            <form method="POST" action="/actions/activity_status.php" class="cs-form border-top mt-5">
                                                                <h3 class="p-3">Beheren</h3>
                                                                
                                                                <button style="max-width: 200px" type="button" class="my-2 mx-5 btn btn-primary w-100" data-bs-target="#ToggleEdit<?php echo $row["id"]; ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Activiteit bewerken</button>

                                                                <button style="max-width: 200px" type="button" class="my-2 mx-5 btn btn-danger w-100" data-bs-target="#ToggleDelete<?php echo $row["id"]; ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Activiteit verwijderen</button>

                                                                <div class="modal-footer mt-5">
                                                                    <?php
                                                                        echo '<input name="id" type="hidden" value=" ' . $row["id"] . ' ">';
                                                                        if ($row['status'] == 'open') {
                                                                            echo '<i class="text-muted">De activiteit staat momenteel <b class="text-success">open</b></i>';
                                                                            echo '<input name="status" type="hidden" value="' . $row["status"] . '">';
                                                                            echo '<input type="submit" style="max-width: 200px" class="btn btn-danger w-100" value="Activiteit sluiten"/>';
                                                                        } else {
                                                                            echo '<i class="text-muted">De activiteit staat momenteel <b class="text-danger">gesloten</b></i>';
                                                                            echo '<input name="status" type="hidden" value="' . $row["status"] . '">';
                                                                            echo '<input type="submit" style="max-width: 200px" class="btn btn-success w-100" value="Activiteit openen"/>';
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete modal -->
                                            <div class="text-start modal fade" id="ToggleDelete<?php echo $row["id"]; ?>" aria-hidden="true" aria-labelledby="ToggleLabel2" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header mx-2">
                                                            <h5 class="modal-title" id="ToggleLabel2">Weet je zeker dat je "<?php echo lcfirst($row['naam']); ?>" wilt verwijderen? </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="/actions/activity_delete.php" method="POST">
                                                                <input name='id' type='hidden' value='<?php echo $row["id"]; ?>' />
                                                                <button type='submit' style="max-width: 200px" class="my-4 d-block mx-auto btn btn-danger w-100">Activiteit verwijderen</button>
                                                            </form>
                                                            <small class="form-text text-muted">Na het <span class="text-danger">verwijderen</span> van een activiteit is er geen mogelijkheid om dit terug te draaien.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit modal -->
                                            <div class="text-start modal fade" id="ToggleEdit<?php echo $row["id"]; ?>" aria-hidden="true" aria-labelledby="ToggleLabel2" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ToggleLabel2">Bewerk <?php echo lcfirst($row['naam']); ?> </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="text-right modal-body">
                                                            <form action="/actions/activity_edit.php" enctype="multipart/form-data" method="POST">
                                                                <input name='id' type='hidden' value='<?php echo $row["id"]; ?>' />
                                                                
                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitStatus">Status</label>
                                                                    <select name="status" class="form-select">
                                                                        <option <?php if ($row["status"] == "open") { echo "selected"; } ?> value="open">✅ Geopend</option>
                                                                        <option <?php if ($row["status"] == "gesloten") { echo "selected"; } ?> value="gesloten">❌ Gesloten</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="d-block my-auto" for="activiteitNaam">Type</label>
                                                                    <select name="type" class="form-select">
                                                                        <option <?php if ($row["type"] == "catering") { echo "selected"; } ?> value="catering">Catering</option>
                                                                        <option <?php if ($row["type"] == "event") { echo "selected"; } ?> value="event">Evenement</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitNaam">Naam</label>
                                                                    <input name="naam" type="text" class="form-control" aria-describedby="activiteitNaam" value="<?php echo $row['naam']; ?>" placeholder="Vul de naam in van de activiteit">
                                                                    <small class="form-text text-muted"><span class="text-danger">*</span> Dit veld is verplicht</small>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitBeschrijving">Beschrijving</label>
                                                                    <input name="beschrijving" type="text" class="form-control" aria-describedby="activiteitBeschrijving" value="<?php echo $row['omschrijving']; ?>" placeholder="Vul de beschrijving in van de activiteit">
                                                                </div>


                                                                <div class="form-group mb-3">
                                                                    <div style="height: 100px; width: 100px; background-image: url('<?php 
                                                                            if ($activiteitImg) {
                                                                                echo $activiteitImg;
                                                                            } else {
                                                                                echo "/img/main/geenImg.jpeg";
                                                                            }
                                                                        ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;" class="bg-primary border rounded-circle my-3 mx-auto"></div>
                                                                    <label class="form-label" for="customFile">Afbeelding veranderen</label>
                                                                    <input name="img" type="file" class="form-control" id="customFile" />
                                                                </div>

                                                                <div class="modal-footer p-0 pt-3">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                                                    <button name="submit" type="submit" class="btn btn-primary">Bewerken</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn btn-primary" data-bs-toggle="modal" href="#Toggle<?php echo $row['id'] ?>" role="button">Beheren</a>
                                        </div>
                                    </div>

                                    <?php

                                    }
                                } else {
                                    echo '<span>Er zijn geen evenementen aangemaakt.</span>';
                                }

                                ?>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-sm-12 col-md-6">

                    <div class="card my-3">
                        <div class="card-header">
                            Catering
                        </div>
                        <div class="card-body">
                            

                            <div class="mx-auto text-center">
                                
                                <?php
                                
                                if ($cateringResult) {
                                    foreach($cateringResult as $row) {
                                        $activiteitImg = $row['img'];
                                ?>

                                    <div class="rounded bg-light mb-4 mx-auto border" style="max-width: 680px; overflow: hidden;">
                                    
                                        <div class='w-100' style="background-image: url('<?php 
                                            if ($activiteitImg) {
                                                echo $activiteitImg;
                                            } else {
                                                echo "/img/main/geenImg.jpeg"; //Dit kan een standaard image worden voor als er geen image in de database staat.
                                            }
                                        ?>'); background-position: center; background-size: cover; background-repeat: no-repeat; height: 220px;">
                                        </div>
                                        <div class="d-md-flex justify-content-between py-3 px-5 bg-light">
                                            <h3 class="my-auto"><?php echo $row['naam']; ?></h3>


                                            <!-- Manage modal -->
                                            <div class="modal fade" id="Toggle<?php echo $row['id'] ?>" aria-hidden="true" aria-labelledby="ToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ToggleLabel">Beheren <?php echo lcfirst($row['naam']) ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            
                                                            <div style="height: 100px; width: 100px; background-image: url('<?php 
                                                                if ($activiteitImg) {
                                                                    echo $activiteitImg;
                                                                } else {
                                                                    echo "/img/main/geenImg.jpeg";
                                                                }
                                                            ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;" class="bg-primary border rounded-circle my-3 mx-auto"></div>

                                                            <?php echo $row['omschrijving']; ?>

                                                            <form method="POST" action="/actions/activity_status.php" class="cs-form border-top mt-5">
                                                                <h3 class="p-3">Beheren</h3>
                                                                
                                                                <button style="max-width: 200px" type="button" class="my-2 mx-5 btn btn-primary w-100" data-bs-target="#ToggleEdit<?php echo $row["id"]; ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Activiteit bewerken</button>

                                                                <button style="max-width: 200px" type="button" class="my-2 mx-5 btn btn-danger w-100" data-bs-target="#ToggleDelete<?php echo $row["id"]; ?>" data-bs-toggle="modal" data-bs-dismiss="modal">Activiteit verwijderen</button>

                                                                <div class="modal-footer mt-5">
                                                                    <?php
                                                                        echo '<input name="id" type="hidden" value=" ' . $row["id"] . ' ">';
                                                                        if ($row['status'] == 'open') {
                                                                            echo '<i class="text-muted">De activiteit staat momenteel <b class="text-success">open</b></i>';
                                                                            echo '<input name="status" type="hidden" value="' . $row["status"] . '">';
                                                                            echo '<input type="submit" style="max-width: 200px" class="btn btn-danger w-100" value="Activiteit sluiten"/>';
                                                                        } else {
                                                                            echo '<i class="text-muted">De activiteit staat momenteel <b class="text-danger">gesloten</b></i>';
                                                                            echo '<input name="status" type="hidden" value="' . $row["status"] . '">';
                                                                            echo '<input type="submit" style="max-width: 200px" class="btn btn-success w-100" value="Activiteit openen"/>';
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete modal -->
                                            <div class="text-start modal fade" id="ToggleDelete<?php echo $row["id"]; ?>" aria-hidden="true" aria-labelledby="ToggleLabel2" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header mx-2">
                                                            <h5 class="modal-title" id="ToggleLabel2">Weet je zeker dat je "<?php echo lcfirst($row['naam']); ?>" wilt verwijderen? </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="/actions/activity_delete.php" method="POST">
                                                                <input name='id' type='hidden' value='<?php echo $row["id"]; ?>' />
                                                                <button type='submit' style="max-width: 200px" class="my-4 d-block mx-auto btn btn-danger w-100">Activiteit verwijderen</button>
                                                            </form>
                                                            <small class="form-text text-muted">Na het <span class="text-danger">verwijderen</span> van een activiteit is er geen mogelijkheid om dit terug te draaien.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit modal -->
                                            <div class="text-start modal fade" id="ToggleEdit<?php echo $row["id"]; ?>" aria-hidden="true" aria-labelledby="ToggleLabel2" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="ToggleLabel2">Bewerk <?php echo lcfirst($row['naam']); ?> </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="text-right modal-body">
                                                            <form action="/actions/activity_edit.php" enctype="multipart/form-data" method="POST">
                                                                <input name='id' type='hidden' value='<?php echo $row["id"]; ?>' />
                                                                
                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitStatus">Status</label>
                                                                    <select name="status" class="form-select">
                                                                        <option <?php if ($row["status"] == "open") { echo "selected"; } ?> value="open">✅ Geopend</option>
                                                                        <option <?php if ($row["status"] == "gesloten") { echo "selected"; } ?> value="gesloten">❌ Gesloten</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="d-block my-auto" for="activiteitNaam">Type</label>
                                                                    <select name="type" class="form-select">
                                                                        <option <?php if ($row["type"] == "catering") { echo "selected"; } ?> value="catering">Catering</option>
                                                                        <option <?php if ($row["type"] == "event") { echo "selected"; } ?> value="event">Evenement</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitNaam">Naam</label>
                                                                    <input name="naam" type="text" class="form-control" aria-describedby="activiteitNaam" value="<?php echo $row['naam']; ?>" placeholder="Vul de naam in van de activiteit">
                                                                    <small class="form-text text-muted"><span class="text-danger">*</span> Dit veld is verplicht</small>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label for="activiteitBeschrijving">Beschrijving</label>
                                                                    <input name="beschrijving" type="text" class="form-control" aria-describedby="activiteitBeschrijving" value="<?php echo $row['omschrijving']; ?>" placeholder="Vul de beschrijving in van de activiteit">
                                                                </div>


                                                                <div class="form-group mb-3">
                                                                    <div style="height: 100px; width: 100px; background-image: url('<?php 
                                                                            if ($activiteitImg) {
                                                                                echo $activiteitImg;
                                                                            } else {
                                                                                echo "/img/main/geenImg.jpeg";
                                                                            }
                                                                        ?>'); background-position: center; background-size: cover; background-repeat: no-repeat;" class="bg-primary border rounded-circle my-3 mx-auto"></div>
                                                                    <label class="form-label" for="customFile">Afbeelding veranderen</label>
                                                                    <input name="img" type="file" class="form-control" id="customFile" />
                                                                </div>

                                                                <div class="modal-footer p-0 pt-3">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                                                    <button name="submit" type="submit" class="btn btn-primary">Bewerken</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn btn-primary" data-bs-toggle="modal" href="#Toggle<?php echo $row['id'] ?>" role="button">Beheren</a>
                                        </div>
                                    </div>

                                    <?php

                                    }
                                } else {
                                    echo '<span>Er zijn geen consumpties aangemaakt.</span>';
                                }

                                ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>