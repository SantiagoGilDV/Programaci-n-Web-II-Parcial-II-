<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$result = $conn->query("SELECT id, Nombre_Usuario, rol_id FROM usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
</head>
<body>

<h2>Lista de usuarios</h2>

<?php while ($u = $result->fetch_assoc()): ?>
    <p>
        <?php echo $u['Nombre_Usuario']; ?>
        <?php if ($u['rol_id'] != 1): ?>
            <a href="hacer_admin.php?id=<?php echo $u['id']; ?>">
                Hacer administrador
            </a>
        <?php endif; ?>
    </p>
<?php endwhile; ?>

<a href="bienvenida.php">Volver</a>

</body>
</html>
