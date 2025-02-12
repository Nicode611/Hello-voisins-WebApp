<?php 
    session_set_cookie_params(3600);
    session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/create-account.css">
    <title>Créez votre compte</title>
</head>
<body>
    <div id="validation"><?php if (isset($_SESSION["success"])) { echo $_SESSION["success"]; unset($_SESSION["success"]); } ?></div>
    <div id="error"><?php if (isset($_SESSION["error"])) { echo $_SESSION["error"]; unset($_SESSION["error"]); } ?></div>

    <div class="sign-in-container">
        <h2>Créez votre compte</h2>
        <form class="sign-in-form" action="../scripts/connection-scripts/script-create-account.php" method="POST">
            <label for="signInFirstName">Prénom</label>
            <input type="text" name="signInFirstName" id="signInFirstName">
            <label for="signInLastName">Nom</label>
            <input type="text" name="signInLastName" id="signInLastName">
            <label for="signInPhone">Téléphone</label>
            <input type="tel" name="signInPhone" id="signInPhone" pattern="[0-9]{10}">
            <label for="signInEmail">Email :</label>
            <input type="email" name="signInEmail" id="signInEmail" required>
            <label for="signInPassword">Mot de passe :</label>
            <input type="password" name="signInPassword" id="signInPassword" required pattern="^(?=.*[0-9])(?=.*[?!@#$%^&*-_])[A-Za-z0-9?!@#$%^&*-/]{8,}$" title="Le mot de passe ne doit pas contenir d'accents et doit contenir 8 caracteres dont 1 chiffre et 1 caractere special (? ! @ # $ % ^ & * - _)">
            <label for="signInConfirmPassword">Confirmez le mot de passe :</label>
            <input type="password" name="signInConfirmPassword" id="signInConfirmPassword" required pattern="^(?=.*[0-9])(?=.*[?!@#$%^&*-_])[A-Za-z0-9?!@#$%^&*-_]{8,}$" title="Le mot de passe ne doit pas contenir d'accents et doit contenir 8 caracteres dont 1 chiffre et 1 caractere special (? ! @ # $ % ^ & * - _)">
            <p class="format-pswd">Le mot de passe ne doit pas contenir d'accents et doit contenir 8 caracteres dont 1 chiffre et 1 caractere special (? ! @ # $ % ^ & * - _)</p>
            <input type="submit" name="submit_create" value="Valider">
        </form>
    </div>
</body>
</html>