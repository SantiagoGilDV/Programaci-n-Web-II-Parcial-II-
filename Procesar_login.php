<?php
session_start();
require 'conexion.php';

if (!isset($_POST['Nombre_Usuario'], $_POST['Contrasenia'])) {
    header("Location: login.php");
    exit;
}

$usuario = trim($_POST['Nombre_Usuario']);
$clave = trim($_POST['Contrasenia']);

$sql = "SELECT u.Id, u.Nombre_Usuario, u.Contrasenia, r.Nombre AS Rol
        FROM usuario u
        JOIN roles r ON u.Rol_Id = r.Id
        WHERE u.Nombre_Usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    if ($clave === $user['Contrasenia']) {

        $_SESSION['id_usuario'] = $user['Id'];
        $_SESSION['Nombre_Usuario'] = $user['Nombre_Usuario'];
        $_SESSION['rol'] = $user['Rol'];

        if ($user['Rol'] === 'admin') {
            header("Location: admin/panel_admin.php");
        } else {
            header("Location: bienvenida.php");
        }
        exit;
    }
}

// ❌ SI LLEGA ACÁ → ERROR
header("Location: login.php?error=Usuario o contraseña incorrectos");
exit;

