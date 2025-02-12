<?php


$includeFile = "../../config/db/db.php";
if (file_exists($includeFile)) { include($includeFile); } else { echo "Le fichier $includeFile n'a pas été trouvé."; }
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$contactId = $_POST["contact_id"];

$sql = "DELETE FROM contacts WHERE id='$contactId'";

if ($conn->query($sql) === TRUE) {

    $response = 'deleted';
    echo json_encode($response);
}

$conn->close();


