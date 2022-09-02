<?php
    require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");

    // Authenticatie adminpaneel \\
    session_start();
    if (empty($_SESSION["authenticated"]) ||  $_SESSION["authenticated"] !== TRUE) {
        header("location: login.php");
        exit;
    }
    
    $code = $_POST['code'];
    $category = $_POST['category'];

    switch ($category) {
        case 'event':

            $stmt = $pdo->prepare('SELECT * FROM glrevents_qrcodes WHERE code = :code AND category = "event"');
            $stmt->execute([':code' => $code]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;

            if($row['code'] === $code){

                if($row['gebruikt'] == 0){

                    $goed =
                    
                    '<div class="min-vh-100 w-100 text-center QRsucces d-flex justify-content-center flex-column">
                        <table class="table border-0">
                            <tr>
                                <td class="text-center text-success display-1 fw-bold pb-3 border-0">' . ucfirst($row['type']) . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-success fs-4 pb-3 border-0">' . $row['naam'] . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-success fs-5 border-0">' . $row['functie'] . '</td>
                            </tr>
                        </table>
                        <i class="text-center pb-5 fa icon fa-solid fa-check text-success"></i>
                        <div class="text-center">
                            <button class="btn btn-success btn-lg mb-5" onclick="tick()">Volgende</button>
                        </div>
                    </div>';

                    echo $goed;

                    $data = [
                        'code' => $code,
                    ];
                    $sql = "UPDATE glrevents_qrcodes SET gebruikt=1 WHERE code = :code";
                    $stmt= $pdo->prepare($sql);
                    $stmt->execute($data);

                } else {
                    $gebruikt =

                    '<div class="min-vh-100 w-100 text-center QRgebruikt d-flex justify-content-center flex-column">
                        <table class="table border-0">
                            <tr>
                                <td class="text-center text-dark display-1 fw-bold pb-3 border-0">' . ucfirst($row['type']) . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-dark fs-4 pb-3 border-0">' . $row['naam'] . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-dark fs-5 border-0">' . $row['functie'] . '</td>
                            </tr>
                        </table>
                        <i class="text-center pb-3 fa icon fas fa-times text-dark"></i>
                        <h3 class="pb-2 text-dark">QR code is al gebruikt</h3>
                        <div class="text-center">
                            <button class="btn btn-dark btn-lg mb-5" onclick="tick()">Volgende</button>
                        </div>
                    </div>';
            
                    echo $gebruikt;
                }

            } else{
                $nietgoed =

                '<div class="min-vh-100 w-100 text-center QRfout d-flex justify-content-center flex-column">
                        <i class="text-center pb-5 fa icon fas fa-question text-dark"></i>
                        <h3 class="pb-4 text-dark">Ongeldige scan of QR code</h3>
                        <div class="text-center">
                            <button class="btn btn-dark btn-lg mb-3" onclick="tick()">Volgende</button>
                        </div>
                    </div>';

                echo $nietgoed;
            }

            break;
        case 'catering':

            $stmt = $pdo->prepare('SELECT * FROM glrevents_qrcodes WHERE code = :code AND category = "catering"');
            $stmt->execute([':code' => $code]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;

            if($row['code'] === $code){

                if($row['gebruikt'] == 0){

                    $goed =
                    
                    '<div class="min-vh-100 w-100 text-center QRsucces d-flex justify-content-center flex-column">
                        <table class="table border-0">
                            <tr>
                                <td class="text-center text-success display-1 fw-bold pb-3 border-0">' . ucfirst($row['type']) . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-success fs-4 pb-3 border-0">' . $row['naam'] . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-success fs-5 border-0">' . $row['functie'] . '</td>
                            </tr>
                        </table>
                        <i class="text-center pb-5 fa icon fa-solid fa-check text-success"></i>
                        <div class="text-center">
                            <button class="btn btn-success btn-lg mb-5" onclick="tick()">Volgende</button>
                        </div>
                    </div>';

                    echo $goed;

                    $data = [
                        'code' => $code,
                    ];
                    $sql = "UPDATE glrevents_qrcodes SET gebruikt=1 WHERE code = :code";
                    $stmt= $pdo->prepare($sql);
                    $stmt->execute($data);

                } else {
                    $gebruikt =

                    '<div class="min-vh-100 w-100 text-center QRgebruikt d-flex justify-content-center flex-column">
                        <table class="table border-0">
                            <tr>
                                <td class="text-center text-dark display-1 fw-bold pb-3 border-0">' . ucfirst($row['type']) . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-dark fs-4 pb-3 border-0">' . $row['naam'] . '</td>
                            </tr>
                            <tr>
                                <td class="text-center text-dark fs-5 border-0">' . $row['functie'] . '</td>
                            </tr>
                        </table>
                        <i class="text-center pb-3 fa icon fas fa-times text-dark"></i>
                        <h3 class="pb-2 text-dark">QR code is al gebruikt</h3>
                        <div class="text-center">
                            <button class="btn btn-dark btn-lg mb-5" onclick="tick()">Volgende</button>
                        </div>
                    </div>';
            
                    echo $gebruikt;
                }

            } else{
                $nietgoed =

                '<div class="min-vh-100 w-100 text-center QRfout d-flex justify-content-center flex-column">
                        <i class="text-center pb-5 fa icon fas fa-question text-dark"></i>
                        <h3 class="pb-4 text-dark">Ongeldige scan of QR code</h3>
                        <div class="text-center">
                            <button class="btn btn-dark btn-lg mb-3" onclick="tick()">Volgende</button>
                        </div>
                    </div>';

                echo $nietgoed;
            }

            break;
        
        default:

            $nietgoed =

            '<div class="min-vh-100 w-100 text-center QRfout d-flex justify-content-center flex-column">
                    <i class="text-center pb-5 fa icon fas fa-question text-dark"></i>
                    <h3 class="pb-4 text-dark">Ongeldige scan of QR code</h3>
                    <div class="text-center">
                        <button class="btn btn-dark btn-lg mb-3" onclick="tick()">Volgende</button>
                    </div>
                </div>';

            echo $nietgoed;
            
            break;
    }

?>