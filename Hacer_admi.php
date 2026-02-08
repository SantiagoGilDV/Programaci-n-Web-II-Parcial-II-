<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$id = $_GET['id'];
$conn->query("UPDATE usuario SET rol_id = 1 WHERE id = $id");

header("Location: lista_usuarios.php");
