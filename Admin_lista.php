<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$result = $conn->query("SELECT * FROM lista");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel administrador</title>
</head>
<body>

<h2>Panel de administración</h2>

<a href="agregar_item.php">Agregar nuevo</a>
<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['titulo']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td>
            <?php if ($row['imagen']): ?>
                <img src="imagenes/<?php echo $row['imagen']; ?>" width="80">
            <?php endif; ?>
        </td>
        <td>
            <a href="editar_item.php?id=<?php echo $row['id']; ?>">Editar</a> |
            <a href="eliminar_item.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="bienvenida.php">Volver</a>

</body>
</html>
