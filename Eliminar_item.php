<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$id = $_GET['id'];
$conn->query("DELETE FROM lista WHERE id=$id");

header("Location: admin_lista.php");
