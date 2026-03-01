<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['Nombre_Usuario'] )) {
    header("Location: login.php");
    exit;
}
$esAdmin = (isset($_SESSION['Nombre_Usuario']) && $_SESSION['usuario'] === 'admin');
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
<?php

?>
    <div class="bienvenida-container">
        <h2>Bienvenido <span class="resaltado"><?php echo $_SESSION['Nombre_Usuario']; ?></span></h2>
        <p>Rol: <span class="resaltado"><?php echo $_SESSION['rol']; ?></span></p>

        <a href="index.php">Volver al inicio</a>
        <?php if (!$esAdmin ): ?>
        <a href="Perfil.php">Mi perfil</a>
        <?php endif; ?>
        <a class="secundario" href="logout.php">Cerrar sesión</a>

    </div>

</body>

</html>
</body>

</html>