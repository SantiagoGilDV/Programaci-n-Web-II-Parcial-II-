<?php
require 'conexion.php';

$usuario = $_POST['Nombre_Usuario'];
$clave = $_POST['Contrasenia'];

$sql = "SELECT u.*, r.nombre AS rol
        FROM usuario u
        JOIN roles r ON u.rol_id = r.id
        WHERE u.Nombre_Usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($clave, $user['Contrasenia'])) {
        $_SESSION['id_usuario'] = $user['id'];
        $_SESSION['usuario'] = $user['Nombre_Usuario'];
        $_SESSION['rol'] = $user['rol'];

        header("Location: bienvenida.php");
        exit;
    }
}

header("Location: login.php?error=Usuario o contraseña incorrectos");
