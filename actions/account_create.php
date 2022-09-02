<?php

require($_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php");

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

$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];

?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../lib/css/bootstrap/bootstrap.min.css">
        <script type="text/javascript" src="../lib/js/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="../lib/js/jquery/jquery-3.6.0.min.js"></script>

        <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="h-100 d-flex justify-content-center align-items-center">
			<div class="rounded d-flex flex-column align-items-center customwidth-400 p-4 shadow-sm bg-light">
				<h1 class="pb-3">Creëer een account</h1>
				<form action="auth.php" method="post">
					<div class="d-flex flex-column">
                        <input type="hidden" name="token" id="token" value="<?= $token; ?>" />
                        <small class="form-text text-muted"><span class="text-danger">*</span> Dit veld is verplicht</small>
						<div class="mb-1 d-flex">
							<label for="username" class="customlabel rounded-start">
								<i id="check" class="fas fa-user"></i>
							</label>
							<input class="customtext rounded-end" type="text" name="username" placeholder="Gebruikersnaam" id="username" required>
						</div>
                        <small class="form-text text-muted"><span class="text-danger">*</span> Dit veld is verplicht (Minimaal 1 hoofdletter, kleine letter en 1 leesteken)</small>
						<div class="d-flex">
							<label for="password" class="customlabel rounded-start">
								<i class="fas fa-lock"></i>
							</label>
							<input class="customtext rounded-end" type="password" name="password" placeholder="Wachtwoord" id="password" required>
						</div>
                        <hr>
                        <div>
                            <h5>Permissies</h5>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-column ">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="event">
                                <label class="form-check-label" for="event">
                                    Evenement-instellingen
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="activiteit">
                                <label class="form-check-label" for="activiteit">
                                    Activiteiten
                                </label>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="qr-code">
                                <label class="form-check-label" for="qr-code">
                                    Qr Codes
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="scanner">
                                <label class="form-check-label" for="scanner">
                                    Scanner
                                </label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-center" id="message"></p>
                        </div>
						<input class="customsubmit rounded btn btn-primary fw-bold py-3" type="submit" value="Account aanmaken" name="register" id="submit" >
					</div>
				</form>
			</div>
		</div>
	</body>

    <script>

$(document).ready(function () {
    $("#username").keyup(function () {
        let input = this.value;
        let userReg = new RegExp('^[a-z0-9_-]{3,16}$')

        if (userReg.test(input)) {
            $("#check").removeClass("fas fa-user").addClass("fa-solid fa-check")
        } else {
            $("#check").removeClass("fa-solid fa-check").addClass("fas fa-user")
        }
    })

    $("#submit").click(function (e) {
        e.preventDefault()
        let user = $("#username").val();
        let pass = $("#password").val();
        let token = $("#token").val();
        let checkbox = $("input[type=checkbox]:checked")
        const perms = []
        
        if (user.length == "" || pass.length == ""){
            $("#message").html("Vul alle velden in")
            $("#message").addClass("text-danger")
            return false;
        } else {
            if ($(checkbox).length > 0 ) {
                $(checkbox).each(function(){
                let checked = $(this).attr('id')
                perms.push(checked)
                console.log(perms)
                    
                })
                $.ajax({
                    type: "POST",
                    url: "/actions/account_reg.php",
                    data: {token: token, user: user, pass: pass, perms: perms},
                    success: function (feedback) {
                        $("#message").html(feedback)
                        $("#message").addClass("text-danger")
                    }
                })
            } else {
                $("#message").html("Selecteer minimaal 1 vak")
                $("#message").addClass("text-danger")
            }
           
        }
    })
})

</script>

    <style>
    html,
    body {
        height: 100%;
    }

    .customwidth-400 {
        width: 400px;
    }

	.customlabel {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 50px;
		height: 50px;
		background-color: #0275d8;
		color: #ffffff;
	}

	.customtext {
		width: 310px;
		height: 50px;
		border: 1px solid #dee0e4;
		margin-bottom: 20px;
		padding: 0 15px;
	}
    </style>

</html>