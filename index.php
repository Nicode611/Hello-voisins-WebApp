<?php 
    session_set_cookie_params(3600);
    session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <title>Hello voisins</title>
</head>
<body>
    
    <h1>Hello voisins</h1>
    <p class="liste"></p>
    <a href="pages/connection.php"><button class="connect-btns">Se connecter</button></a>
    <a href="pages/create-account.php"><button class="connect-btns">Create account</button></a>


<script src="./assets/js/test.js/get.js"></script>
</body>
</html>