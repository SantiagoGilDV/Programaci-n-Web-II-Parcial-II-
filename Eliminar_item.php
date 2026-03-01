<?php
session_start();
require_once "Conexion.php";

$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));
if (!$esAdmin || !$hayUser) {
    header("Location: error.php");
    exit();
}


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido");

$stmt = $conn->prepare("DELETE FROM artista WHERE Id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: Admin_lista.php");
    exit;
} else {
    die("Error al eliminar: " . $stmt->error);
}