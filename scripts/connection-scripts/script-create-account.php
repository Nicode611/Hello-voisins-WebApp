<?php

use Symfony\Component\Console\Event\ConsoleEvent;

session_destroy();
session_set_cookie_params(3600);
session_start(); 


if (isset($_POST["submit_create"])) {

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $firstName = htmlspecialchars($_POST["signInFirstName"], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST["signInLastName"], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST["signInPhone"], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST["signInEmail"], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST["signInPassword"], ENT_QUOTES, 'UTF-8');
    $confirmPassword = htmlspecialchars($_POST["signInConfirmPassword"], ENT_QUOTES, 'UTF-8');
    $defaultProfileImg = "assets/images/default-profile-img/user-default.png";

    if (strlen($password) >= 8 && preg_match("/[0-9]/", $password) && preg_match("/[!?@#$%^&*]/", $password)) { 

    if ($password == $confirmPassword) {
        
        $validPassword = $password;
        $hash_password = password_hash($validPassword, PASSWORD_DEFAULT);
        $adress = 'yes';

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION["error"] = "<p class='error'>Adresse email déja utilisée.</p>";
            $conn->close();
            header("Location: ../../pages/create-account.php");
            exit();
        }


        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, profile_img_path, adress) VALUES (?, ?, ?, ?, ?, ?, ?)";


        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erreur de préparation de la requête : " . $conn->error);
        }

        // Liaison des paramètres
        $stmt->bind_param('sssssss', $firstName, $lastName, $email, $hash_password, $phone, $defaultProfileImg, $adress);

        if ($stmt->execute()) {
            ?> <span class="validation">Compte crée, connectez vous</span> <?php
            $_SESSION["success"] = "<p class='validation'>Compte crée, connectez vous !</p>";
            $conn->close();
            header("Location: ../../pages/connection.php");
            exit();

            } else {
                $_SESSION["error"] = "<p class='error'>Erreur</p>";
                $stmt->close();
                $conn->close();
                header("Location: ../../pages/create-account.php");
                exit();
            }
        } else {
            $_SESSION["error"] = "<p class='error'>Les mdps ne correspondent pas.</p>";
            $conn->close();
            header("Location: ../../pages/create-account.php");
            exit();
        }
    } else {
        $_SESSION["error"] = "<p class='error'>Format incorrect.</p>";
        $conn->close();
        header("Location: ../../pages/create-account.php");
        exit();
    }
} else {
    $_SESSION["error"] = "<p class='error'>Format incorrect.</p>";
    $conn->close();
    header("Location: ../../pages/create-account.php");
    exit();
};

?>