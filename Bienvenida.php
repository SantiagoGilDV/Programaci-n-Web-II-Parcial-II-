<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link rel="stylesheet" href="./css/Bienvemida.css">
</head>
<body>

<div class="bienvenida-container">
    <h2>Bienvenido <span class="resaltado"><?php echo $_SESSION['usuario']; ?></span></h2>
    <p>Rol: <span class="resaltado"><?php echo $_SESSION['rol']; ?></span></p>

    <a href="index.php">Volver al inicio</a>
    <a class="secundario" href="Cerrar_sesion.php">Cerrar sesión</a>
</div>

</body>
</html>
</body>
</html>