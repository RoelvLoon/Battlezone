<?php
// Authenticatie adminpaneel \\
session_start();
if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["perms"]["event"]) && empty($_SESSION["perms"]["admin"])) {
    echo '<script>window.history.back();</script>';
    exit;
}

// Sidebar active class
include($_SERVER["DOCUMENT_ROOT"] . "/actions/set_active.php");

require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");
require($_SERVER["DOCUMENT_ROOT"] . "/actions/generate_bs5_color.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["offline"])) {
        $offline = $_POST["offline"];
    }

    if (isset($_POST["title"])) {
        $title = $_POST["title"];
    }

    if (isset($_POST["description"])) {
        $description = $_POST["description"];
    }

    if (isset($_POST["button_option"])) {
        $button_option = $_POST["button_option"];
    }

    if (isset($_POST["url_button_text"])) {
        $url_button_text = $_POST["url_button_text"];
    }

    if (isset($_POST["url_button"])) {
        $url_button = $_POST["url_button"];
    }

    if (isset($_POST["button_text"])) {
        $button_text = $_POST["button_text"];
    }

    if (isset($_POST["qr_instructions"])) {
        $qr_instructions = $_POST["qr_instructions"];
    }

    if (isset($_POST["logo_size"])) {
        $logo_size = $_POST["logo_size"];
    }

    if (isset($_POST["main_color"])) {
        $main = $_POST["main_color"];
    }

    if (isset($_POST["nav_color"])) {
        $nav = $_POST["nav_color"];
    }

    if (isset($_POST["background_color"])) {
        $background = $_POST["background_color"];
    }

    if (isset($_FILES["nav_logo"])) {
        $file = $_FILES["nav_logo"];

        if (file_exists($_FILES['nav_logo']['tmp_name']) || is_uploaded_file($_FILES['nav_logo']['tmp_name'])) {
        
            $fileName = $_FILES["nav_logo"]["name"];
            $fileTmpName = $_FILES["nav_logo"]["tmp_name"];
            $fileSize = $_FILES["nav_logo"]["size"];
            $fileError = $_FILES["nav_logo"]["error"];
            $fileType = $_FILES["nav_logo"]["type"];

            $fileExt = explode(".", $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array("jpg", "jpeg", "png", "webp", "gif");

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 8192000) {

                        $fileNameNew = uniqid("", true) . "." . $fileActualExt;

                        $fileDestination = "../img/uploads/" . $fileNameNew;

                        move_uploaded_file($fileTmpName, $fileDestination);

                        $data = [
                            'nav_logo' => "/uploads" . "/" . $fileNameNew,
                        ];
                        $sql = "UPDATE glrevents_settings_event SET 
                        nav_logo = :nav_logo
                        WHERE id = 1";
                        $stmt= $pdo->prepare($sql);
                        $stmt->execute($data);

                        $nav_logo_message = '<small class="text-success">Het logo is succesvol geupload!</small>';

                    } else {
                        $nav_logo_message = '<small class="text-danger">De bestandsgrootte mag maximaal 8MB zijn...</small>';
                    }
                } else {
                    $nav_logo_message = '<small class="text-danger">Er is een fout opgetreden...</small>';
                }

            } else {
                $nav_logo_message = '<small class="text-danger">Je kan alleen .jpg, .png, .webp en .gif bestanden uploaden...</small>';
            }
        }
    }

    if (isset($_FILES["main_logo"])) {
        $file = $_FILES["main_logo"];

        if (file_exists($_FILES['main_logo']['tmp_name']) || is_uploaded_file($_FILES['main_logo']['tmp_name'])) {
        
            $fileName = $_FILES["main_logo"]["name"];
            $fileTmpName = $_FILES["main_logo"]["tmp_name"];
            $fileSize = $_FILES["main_logo"]["size"];
            $fileError = $_FILES["main_logo"]["error"];
            $fileType = $_FILES["main_logo"]["type"];

            $fileExt = explode(".", $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array("jpg", "jpeg", "png", "webp", "gif");

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 8192000) {

                        $fileNameNew = uniqid("", true) . "." . $fileActualExt;

                        $fileDestination = "../img/uploads/" . $fileNameNew;

                        move_uploaded_file($fileTmpName, $fileDestination);

                        $data = [
                            'main_logo' => "/uploads" . "/" . $fileNameNew,
                        ];
                        $sql = "UPDATE glrevents_settings_event SET 
                        main_logo = :main_logo
                        WHERE id = 1";
                        $stmt= $pdo->prepare($sql);
                        $stmt->execute($data);

                        $main_logo_message = '<small class="text-success">Het navigatielogo is succesvol geupload!</small>';

                    } else {
                        $main_logo_message = '<small class="text-danger">De bestandsgrootte mag maximaal 8MB zijn...</small>';
                    }
                } else {
                    $main_logo_message = '<small class="text-danger">Er is een fout opgetreden...</small>';
                }

            } else {
                $main_logo_message = '<small class="text-danger">Je kan alleen .jpg, .png, .webp en .gif bestanden uploaden...</small>';
            }
        }
    }

    if (isset($_FILES["bg_image"])) {
        $file = $_FILES["bg_image"];

        if (file_exists($_FILES['bg_image']['tmp_name']) || is_uploaded_file($_FILES['bg_image']['tmp_name'])) {
            
            $fileName = $_FILES["bg_image"]["name"];
            $fileTmpName = $_FILES["bg_image"]["tmp_name"];
            $fileSize = $_FILES["bg_image"]["size"];
            $fileError = $_FILES["bg_image"]["error"];
            $fileType = $_FILES["bg_image"]["type"];

            $fileExt = explode(".", $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array("jpg", "jpeg", "png", "webp", "gif");

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 8192000) {

                        $fileNameNew = uniqid("", true) . "." . $fileActualExt;

                        $fileDestination = "../img/uploads/" . $fileNameNew;

                        move_uploaded_file($fileTmpName, $fileDestination);

                        $data = [
                            'bg_image' => "/uploads" . "/" . $fileNameNew,
                        ];
                        $sql = "UPDATE glrevents_settings_event SET 
                        bg_image = :bg_image
                        WHERE id = 1";
                        $stmt= $pdo->prepare($sql);
                        $stmt->execute($data);

                        $bg_image_message = '<small class="text-success">De achtergrond is succesvol geupload!</small>';

                    } else {
                        $bg_image_message = '<small class="text-danger">De bestandsgrootte mag maximaal 8MB zijn...</small>';
                    }
                } else {
                    $bg_image_message = '<small class="text-danger">Er is een fout opgetreden...</small>';
                }

            } else {
                $bg_image_message = '<small class="text-danger">Je kan alleen .jpg, .png, .webp en .gif bestanden uploaden...</small>';
            }
        }
    }


    $cssMain = getCSS($main, "main");
    $cssNav = getCSS($nav, "nav");
    $cssBackground = getCSS($background, "background");

    $data = [
        'offline' => $offline,
        'title' => $title,
        'description' => $description,
        'button_option' => $button_option,
        'url_button_text' => $url_button_text,
        'url_button' => $url_button,
        'button_text' => $button_text,
        'qr_instructions' => $qr_instructions,
        'logo_size' => $logo_size,
        'color_main' => $main,
        'color_nav' => $nav,
        'color_background' => $background,
        'main_css' => $cssMain,
        'nav_css' => $cssNav,
        'background_css' => $cssBackground,
    ];
    $sql = "UPDATE glrevents_settings_event SET 
    offline = :offline,
    title = :title,
    description = :description,
    button_option = :button_option,
    url_button_text = :url_button_text,
    url_button = :url_button,
    button_text = :button_text,
    qr_instructions = :qr_instructions,
    logo_size = :logo_size,
    color_main = :color_main,
    color_nav = :color_nav,
    color_background = :color_background,
    main_css = :main_css,
    nav_css = :nav_css,
    background_css = :background_css
    WHERE id = 1";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);


}

// Haal site-gegevens op uit database
$stmt = $pdo->prepare('SELECT * FROM glrevents_settings_event');
$stmt->execute();
$siteData = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>
    
    <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
    <title>Evenementinstellingen | Adminpaneel</title>

    <link rel="stylesheet" href="/lib/css/glrevents/admin.css">

    <script type="text/javascript" src="../lib/js/glrevents/generate_bs5_color.js" defer></script>
    <script type="text/javascript" src="../lib/js/glrevents/settings_event.js" defer></script>
    
    <style id="previewMainCSS">
        <?= $siteData["main_css"] ?>
    </style>
    <style id="previewNavCSS">
        <?= $siteData["nav_css"] ?>
    </style>
    <style id="previewBackgroundCSS">
        <?= $siteData["background_css"] ?>
    </style>
</head>
<body>

    <!-- Topbar mobile -->
    <nav class="d-flex d-lg-none navbar navbar-dark bg-dark w-100" style="height: 58px;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-bars fa-fw fs-3 text-white" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"></i>
            <span class="text-center fs-3 text-white justify-content-center">Evenementinstellingen</span>
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
                <h1 class="text-center">Evenementinstellingen</h1>
                <hr>
            </div>

            <div class="row">
                <div class="col-xxl-9 mb-3">

                    <div class="card">
                        <div class="card-header">Preview</div>
                        <div class="card-body p-0 m-0">
                            <div class="d-flex flex-column" style="height: 70vh;">

                                <header class="h-100 d-flex flex-column">

                                    <nav class="navbar navbar-expand-lg navbar-light bg-nav">
                                        <div class="container-fluid">
                                            <a class="navbar-brand" href="#">
                                                <img id="previewNavLogo" src="/img/<?= $siteData["nav_logo"] ?>" alt="" width="25" height="25" class="d-inline-block align-text-top">
                                                <span id="previewTitleNav" class="ms-1 bg-nav"><?= $siteData["title"] ?></span>
                                            </a>
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHamburger" aria-controls="navbarHamburger" aria-expanded="false" aria-label="Navigatie">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>
                                            <div class="collapse navbar-collapse" id="navbarHamburger">
                                                <ul class="navbar-nav me-auto mb-2 mb-lg-0 px-3 py-1 p-lg-0">
                                                    <li class="nav-item">
                                                        <a class="nav-link bg-nav rounded p-2" href="#">Activiteiten</a>
                                                    </li>
                                                </ul>
                                                <div class="dropdown">
                                                    <span class="d-flex align-items-center text-decoration-none fw-lighter dropdown-toggle" id="dropdownUser">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAD2UExURbGxsa+vr62trbKysrW1tcfHx93d3evr6/Ly8vPz8+3t7eDg4MzMzLi4uLOzs9DQ0P7+/v////b29tjY2La2trq6uunp6fDw8MHBwb29vfj4+MbGxvHx8fn5+eXl5cvLy9ra2vr6+rm5ubCwsMnJyeTk5PT09LS0tPf3976+vv39/c3NzcjIyNnZ2c/Pz9/f39HR0ePj487OztfX17y8vLu7u8XFxdLS0re3t9TU1Ozs7MDAwN7e3ujo6Orq6vz8/O/v77+/v+fn59XV1cLCwuHh4dvb28PDw9bW1tzc3Pv7++Li4sTExMrKyubm5u7u7vX19a6urs05bDQAAAAJcEhZcwAADsIAAA7CARUoSoAAAAKMSURBVFhH7ZdZW9pAFIaDIKuhQQRECFCkxGJbkVoVFAFtXbDr//8zzfJlMssZnXLd90Y853zvEzLjJFocqQ3YQnZjUvi5Mf8FrwrSPvio4SVBZjubyxeKpR0bBQq9oPwm71Qidqt7KKpoBbV6HA9o7KOsoBM084iCgxYaMhqB3UaQ4WquQSPoIMbRpu8DLejy3z+mh6YIKSgrXyDgbR9tgZR1iE8cA+oCKpUq2gKk4B0SEkNqU1IC7wgJCec9BngoQXOEhMwxBngowQfMK3zEAA8l+IR5hRMM8FCCMeYVTtVZUjDBvILpFbTobVCpfMYADyU4061CFwM8lKA8REDC+YIBHkpglZCQaKMtQArO6ZtwgbYAKUgXERE4ukRbgBTQ6zBFU4QWWFmEOGYeeiIaQSaHGGNILYGPRmD1JUPhCg0ZncDKXHPbyZnXUFbQCqx062YRxUfLSRlFFVVg72Trs17G/5RejUu389O7r9Hts7O53LeJ/JyUBc0TN1zC5Sr6PXk4r8Kj2nHvRYUo8KpuMBXgXgiX7U1ZZzhALUQQ2DeYCXByDyj71zFYcltrcccdz7xAXvxRvdv3R73mtCHuTOcRCR9e8IQ+x7qwbOyqx8PiHBFBsI9VM6HIbhAneEbThNF3hDhB/wBNI66R4gQ/6FNEQzv+20wEPbTMWMfPyUTwEy1D4hOaCdK/0DHkPoolAu83OobMo1giuGR73Yw8dgITaF8KNLg4o5ngAQ1TnLMoxwRdNIzBXmSCKerGYB1jwSHxJHiZcZhLrkDzaqcHLwtMMEPdGOykWFCWXu9fpxTmmGBP83Kp5znMMUFtjboxnTDHBFf/uBEVwTbK5jDBn/C/+GOUzemEudTWX/lklWyZ8wkkAAAAAElFTkSuQmCC" alt="PF" width="32" height="32" class="rounded-circle me-2">
                                                        <strong><?= $_SESSION["user"] ?></strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>

                                    <main class="d-flex flex-grow-1 bg-background justify-content-between" id="previewBackgroundImage" style="background-image: url(/img/<?= $siteData["bg_image"] ?>); background-size: cover; background-repeat: no-repeat; background-position: center;">
                                        <div class="container-fluid">
                                            <div class="row h-100">
                                                <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center">
                                                    <img id="previewMainLogo" class="img-fluid" style="width: <?= $siteData["logo_size"] ?>%;" src="/img/<?= $siteData["main_logo"] ?>" alt="Logo">
                                                </div>
                                                <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center">
                                                    <div class="p-3 p-lg-0">
                                                        <h1 id="previewTitle" class="fw-normal display-3"><?= $siteData["title"] ?></h1>
                                                        <h2 id="previewDescription" class="fw-light" maxlength="10"><?= $siteData["description"] ?></h2>
                                                        <div id="algemene_qr_toegang">
                                                            <button id="previewButtonText" class="btn btn-main btn-lg p-2 px-3 mt-3" data-bs-toggle="modal" data-bs-target="#qrcodeModal" onclick="return false;"><?= $siteData["button_text"] ?></button>
                                                        </div>
                                                        <div id="eigen_link">
                                                            <a id="previewURLButtonText" class="btn btn-main btn-lg p-2 px-3 mt-3" href="<?= $siteData["url_button"] ?>"><?= $siteData["url_button_text"] ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </main>

                                </header>

                                <!-- Modal -->
                                <div class="modal fade" id="qrcodeModal" tabindex="-1" aria-labelledby="qrcodeModalLabel" data-bs-backdrop="static" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="qrcodeModalLabel">Hoi <?= $_SESSION["user"] ?>!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="previewQRInstructions" class="mb-0 text-center"><?= $siteData["qr_instructions"] ?></p>
                                                <div class="ratio ratio-1x1 my-3">
                                                    <img src="../img/main/qrcode.png" class="w-100 border border-1 shadow"/>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-main" data-bs-dismiss="modal">Sluiten</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

            </div>
            
            <div class="col-xxl-3">
                
                <div class="bg-light p-3 border rounded">
                    <form action="" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="title" class="form-label">❗Toegang tot website</label>
                            <select class="form-select-sm w-100 border border-2" name="offline" id="offline">
                                <option value="0" <?php if ($siteData["offline"] == 0) { echo "selected"; } ?>>✅ Geopend</option>
                                <option value="1" <?php if ($siteData["offline"] == 1) { echo "selected"; } ?>>❌ Gesloten</option>
                            </select>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <input type="text" placeholder="Titel evenement" name="title" id="title" class="form-control-sm w-100 border border-2" maxlength="50" value="<?= $siteData["title"] ?>"/>
                        </div>
                        <div class="mb-3">
                            <input type="text" placeholder="Beschrijving evenement" name="description" id="description" class="form-control-sm w-100 border border-2" maxlength="255" value="<?= $siteData["description"] ?>"/>
                        </div>
                        <div>
                            <select class="form-select-sm w-100 border border-2" name="button_option" id="button_option">
                                <option value="0" <?php if ($siteData["button_option"] == 0) { echo "selected"; } ?>>Algemene QR toegang</option>
                                <option value="1" <?php if ($siteData["button_option"] == 1) { echo "selected"; } ?>>Eigen link</option>
                                <option value="2" <?php if ($siteData["button_option"] == 2) { echo "selected"; } ?>>Geen knop</option>
                            </select>
                        </div>
                        <div id="algemene_qr_toegang_settings" class="border p-1" style="display: none;">
                            <div class="mb-3">
                                <input type="text" placeholder="Knoptekst" name="button_text" id="button_text" class="form-control-sm w-100 border border-2" value="<?= $siteData["button_text"] ?>"/>
                            </div>
                            <div>
                                <textarea name="qr_instructions" placeholder="QR-code instructies" id="qr_instructions" class="form-control-sm w-100 border border-2" rows="3" maxlength="125"><?= $siteData["qr_instructions"] ?></textarea>
                            </div>
                        </div>
                        <div id="eigen_link_settings" class="border p-1" style="display: none;">
                            <div class="mb-3">
                                <input type="text" placeholder="Knoptekst" name="url_button_text" id="url_button_text" class="form-control-sm w-100 border border-2" value="<?= $siteData["url_button_text"] ?>"/>
                            </div>
                            <div>
                                <input type="text" name="url_button" placeholder="URL" id="url_button" class="form-control-sm w-100 border border-2" value="<?= $siteData["url_button"] ?>"/>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="logo_size" class="form-label">Logo-grootte</label>
                            <input type="range" min="10" max="90" step="1" name="logo_size" id="logo_size" class="form-range-sm w-100" value="<?= $siteData["logo_size"] ?>"/>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="main_color" class="form-label"><small>Algemeen</small></label>
                                <input type="color" name="main_color" id="main_color" class="form-color-sm w-100" value="<?= $siteData["color_main"] ?>"/>
                            </div>
                            <div class="col-4">
                                <label for="main_color" class="form-label"><small>Navigatiebalk</small></label>
                                <input type="color" name="nav_color" id="nav_color" class="form-color-sm w-100" value="<?= $siteData["color_nav"] ?>"/>
                            </div>
                            <div class="col-4">
                                <label for="main_color" class="form-label"><small>Achtergrond</small></label>
                                <input type="color" name="background_color" id="background_color" class="form-color-sm w-100" value="<?= $siteData["color_background"] ?>"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="main_logo" class="form-label">Logo</label>
                            <input class="form-control form-control-sm" id="main_logo" name="main_logo" type="file">
                            <?php if(isset($main_logo_message)) { echo $main_logo_message; } ?>
                        </div>
                        <div class="mb-3">
                            <label for="nav_logo" class="form-label">Klein logo (navigatiebalk)</label>
                            <input class="form-control form-control-sm" id="nav_logo" name="nav_logo" type="file">
                            <?php if(isset($nav_logo_message)) { echo $nav_logo_message; } ?>
                        </div>
                        <div class="mb-3">
                            <label for="bg_image" class="form-label">Achtergrondafbeelding</label>
                            <input class="form-control form-control-sm" id="bg_image" name="bg_image" type="file">
                            <?php if(isset($bg_image_message)) { echo $bg_image_message; } ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>

                    </form>
                </div>
                
            </div>

        </main>

    </div>
</body>
</html>