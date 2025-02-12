<?php
        if (isset($_POST["submit_connect"])) {

        $includeFile = "../config/db/db.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Récupérer les données de localisation envoyées via la requête Ajax
        $email = htmlspecialchars($_POST['logInEmail'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['logInPassword'], ENT_QUOTES, 'UTF-8');

        $email = mysqli_real_escape_string($conn, $email);

        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashMdp = $row["password"];
    
            if (password_verify($password, $hashMdp)) {
                
                session_destroy();
                session_start();
    
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["user_firstName"] = $row["first_name"];
                $_SESSION["user_lastName"] = $row["last_name"];
                $_SESSION["user_email"] = $row["email"];
                $_SESSION["user_password"] = $row["password"];
                $_SESSION["user_phone"] = $row["phone"];
                $_SESSION["user_latitude"] = $row["latitude"];
                $_SESSION["user_longitude"] = $row["longitude"];
                $_SESSION["user_profile_img_path"] = $row["profile_img_path"];
                
                $conn->close();
                header("Location: proximity-chat.php");
                exit();
                
            } else {
                session_start();
                $_SESSION["error"] = "<p class='error'>Mot de passe incorect</p>";
                $conn->close();
                header("Location: connection.php");
                exit();
            }
        } else {
            session_start();
            $_SESSION["error"] = "<p class='error'>L'adresse email n'a pas pu etre trouvée...</p>";
            $conn->close();
            header("Location: connection.php");
            exit();
        }

    };
        
    ?>


