<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("ID inválido");

$stmt = $conn->prepare("UPDATE usuario SET Rol = 'admin' WHERE Id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error al actualizar rol: " . $stmt->error);
}

header("Location: Lista_usuarios.php");
exit;