<?php

require $_SERVER["DOCUMENT_ROOT"] . "/inc/db_config.php";

session_start();

if (isset($_SESSION["authenticated"]) ||  isset($_SESSION["authenticated"]) === TRUE) {
    header("location: index.php");
    exit;
}

$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];


?>

<!DOCTYPE html>
<html lang="nl">
	<head>
        <?php // Include de head.php voor Bootstrap, jQuery en meta tags ?>
        <?php include($_SERVER["DOCUMENT_ROOT"] . '/components/head.php') ?>
        
        <title>Login</title>

        <script src="https://kit.fontawesome.com/3a3e211029.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="h-100 d-flex justify-content-center align-items-center">
			<div class="rounded d-flex flex-column align-items-center customwidth-400 p-4 shadow-sm bg-light">
				<h1 class="pb-3">Login</h1>
				<form action="auth.php" method="post">
					<div class="d-flex flex-column">
                        <input type="hidden" name="token" id="token" value="<?= $token; ?>" />
						<div class="mb-2 d-flex">
							<label for="username" class="customlabel rounded-start">
								<i id="check" class="fas fa-user"></i>
							</label>
							<input class="customtext rounded-end" type="text" name="username" placeholder="Gebruikersnaam" id="username" required>
						</div>
						<div class="mb-2 d-flex">
							<label for="password" class="customlabel rounded-start">
								<i class="fas fa-lock"></i>
							</label>
							<input class="customtext rounded-end" type="password" name="password" placeholder="Wachtwoord" id="password" required>
						</div>
                        <div>
                            <p class="text-center" id="message"></p>
                        </div>
						<input class="customsubmit rounded btn btn-primary fw-bold py-3" type="submit" value="login" name="login" id="submit" >
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
                if (user.length == "" || pass.length == ""){
                    $("#message").html("Vul alle velden in")
                    $("#message").addClass("text-danger")
                    return false;
                } else {
                    $.ajax({
                        type: "POST",
                        url: "./auth.php",
                        data: {token: token, user: user, pass: pass},
                        success: function (feedback) {
                            $("#message").html(feedback)
                            $("#message").addClass("text-danger")
                        }
                    })
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