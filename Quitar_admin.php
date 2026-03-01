<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: error.php");
    exit();
}


$usuarioLog = $_SESSION['Nombre_Usuario'] ?? ($_SESSION['usuario'] ?? '');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("ID inválido");

// Traer usuario target
$stmt = $conn->prepare("SELECT Nombre_Usuario, Rol FROM usuario WHERE Id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$u = $res->fetch_assoc();

if (!$u)
    die("Usuario no encontrado");

$nombreUsuario = $u['Nombre_Usuario'] ?? '';
$rolActual = $u['Rol'] ?? 'user';

// Protecciones:
if (strtolower($nombreUsuario) === 'admin')/*convierte todos los caracteres en mayúsculas a minúsculas*/ {
    die("No se puede quitar el rol admin al usuario admin principal.");
}
if ($usuarioLog !== '' && $usuarioLog === $nombreUsuario) {
    die("No podés quitarte tu propio rol de administrador.");
}
if ($rolActual !== 'admin') {
    header("Location: Lista_usuarios.php");
    exit;
}

// transformar a user
$up = $conn->prepare("UPDATE usuario SET Rol='user' WHERE Id=?");
$up->bind_param("i", $id);

if (!$up->execute()) {
    die("Error al actualizar rol: " . $up->error);
}

header("Location: Lista_usuarios.php");
exit;