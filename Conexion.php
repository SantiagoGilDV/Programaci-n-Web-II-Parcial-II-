<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    header("Location: error.php");
    exit();
}

?>