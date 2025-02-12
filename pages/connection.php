<?php 
    session_set_cookie_params(3600);
    session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/connection.css">
    <title>Connectez-vous</title>
</head>
<body>

<div id="validation"><?php if (isset($_SESSION["success"])) { echo $_SESSION["success"]; unset($_SESSION["success"]); } ?></div>
<div id="error"><?php if (isset($_SESSION["error"])) { echo $_SESSION["error"]; unset($_SESSION["error"]); } ?></div>
    
    <div class="log-in-container">
        <h2>Connectez-vous</h2>
        <form class="log-in-form" method="POST">
            <label for="logInEmail">Email :</label>
            <input type="email" name="logInEmail" id="logInEmail" required>
            <label for="logInPassword">Mot de passe :</label>
            <input type="password" name="logInPassword" id="logInPassword" required>
            <input type="submit" name="submit_connect" value="Se connecter">
        </form>
    </div>

    <?php
        $includeFile = "../scripts/connection-scripts/script-connection.php";
        if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
    ?>

    
    
</body>
</html>