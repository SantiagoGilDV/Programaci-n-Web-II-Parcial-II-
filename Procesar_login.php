<?php
session_start();
require_once "Conexion.php";

$usuario = $_POST['Nombre_Usuario'] ?? '';
$clave = $_POST['Contrasenia'] ?? '';

$sql = "SELECT Id, Nombre_Usuario, Contrasenia, Rol
        FROM usuario
        WHERE Nombre_Usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();


    if ($clave === $user['Contrasenia']) {


        $_SESSION['user_id'] = $user['Id'];
        $_SESSION['Nombre_Usuario'] = $user['Nombre_Usuario'];
        $_SESSION['rol'] = $user['Rol'];


        $_SESSION['id_usuario'] = $user['Id'];
        $_SESSION['usuario'] = $user['Nombre_Usuario'];


        if ($user['Rol'] === 'admin') {
            header("Location: Bienvenida.php");
        } else {
            header("Location: Bienvenida.php");
        }
        exit;
    }
}

header("Location: login.php?error=Usuario%20o%20contrase%C3%B1a%20incorrectos");
exit;
