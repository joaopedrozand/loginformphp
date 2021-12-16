<?php
//session_start();
//if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//    header("location: login.php");
//   exit;
//}
//require_once "conex.php";
//$id = $_SESSION["id"];
//$user = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/solid.css">
    <link rel="stylesheet" href="css/all.min.css">
</head>
<body>
    <!-- <a>Bem vindo, <?php // echo $user ?></a>-->
    <div id="wrapper">
        <div id="opcoes">
            <div class="headerop">
                <div class="headerbutton">
                    <a href="welcome.php"><i class="fas fa-home"></i> Inicio</a>
                </div>
                <div class="headerbutton">
                    <a href="reset-password.php"><i class="fas fa-key"></i> Alterar senha</a>
                </div>
                <div class="headerbutton">
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                </div>
            </div>
        </div>
    <div>
</body>
</html>