<?php

session_start();

require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";


if (isset($_POST["token"])) {
    if (hash_equals($_SESSION["token"], $_POST["token"])) {
        if (empty($_POST["user"]) || empty($_POST["pass"])) {
            exit("Vul alle velden in");
        } else {
    
            $user = $_POST["user"];
            $pass = $_POST["pass"];
    
            $query = $pdo->prepare("SELECT * FROM glrevents_admin WHERE username=:user");
            $query->bindParam("user", $user, PDO::PARAM_STR);
            $query->execute();
            
            $result = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                echo "Fout wachtwoord of gebruikersnaam";
            } else {
                if (password_verify($pass, $result["password"])) {
                    session_regenerate_id();
                    $_SESSION["authenticated"] = TRUE;
                    $_SESSION["user"] = $user;
                    $expPerms = explode(", ", $result["perms"]);
                    foreach($expPerms as $item){
                        $_SESSION["perms"][$item] = $item;
                    }
                    echo '<script>location.href="index.php";</script>';
                    
                } else {
                    echo "Fout wachtwoord of gebruikersnaam";
                }
            }
        }
    } else {
        unset($_SESSION["token"]);
        echo "Foute token";
    }
}


?>