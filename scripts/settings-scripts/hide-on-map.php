<?php

if (isset($_GET["check"])) { 

    session_set_cookie_params(3600);
    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $userId = $_SESSION["user_id"];

    $sql = "UPDATE users SET adress = 'yes' WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        
        $response = array('success' => true, 'message' => 'l\'utilisateur est visible sur la carte');
        echo json_encode($response);
        $conn->close();
        exit();

    } else {
        $_SESSION["error"] = "<p class='error'>Changement non effectués !</p>";
        $conn->close();
        header("Location: ../../pages/settings.php");
        exit();
    }


} else if (isset($_GET["uncheck"])) { 

    session_set_cookie_params(3600);
    session_start();

    $includeFile = "../../config/db/db.php";
    if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $userId = $_SESSION["user_id"];

    $sql = "UPDATE users SET adress = 'no' WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        
        $response = array('success' => true, 'message' => 'l\'utilisateur n\'est plus visible sur la carte');
        echo json_encode($response);
        $conn->close();
        exit();

    } else {
        $_SESSION["error"] = "<p class='error'>Changement non effectués !</p>";
        $conn->close();
        header("Location: ../../pages/settings.php");
        exit();
    }


}