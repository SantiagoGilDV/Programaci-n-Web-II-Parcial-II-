<?php
require 'conexion.php';

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
</head>
<body>

<h2>Bienvenido <?php echo $_SESSION['usuario']; ?></h2>
<p>Rol: <?php echo $_SESSION['rol']; ?></p>

<a href="editar_perfil.php">Editar perfil</a><br>

<?php if ($_SESSION['rol'] === 'admin'): ?>
    <a href="lista_usuarios.php">Administrar usuarios</a><br>
<?php endif; ?>

<a href="logout.php">Cerrar sesión</a>

</body>
</html>
