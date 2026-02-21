<?php
session_start();
require_once "Conexion.php";

function imgSrc($valor) {
    if (empty($valor)) return null;

    $valor = trim($valor);

    
    $valor = preg_replace('#^\./#', '', $valor);

   
    return $valor;
}


$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));

if (!$esAdmin || !$hayUser) {
    die("Acceso denegado");
}

$result = $conn->query("SELECT * FROM artista ORDER BY Id DESC");
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel administrador - Artistas</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

<h2>Panel de administración (Artistas)</h2>

<a class="btn-agregar" href="Agregar_item.php">+ Agregar artista</a>
<br><br>

<table class="tabla-admin">
    <tr>
        <th>ID</th>
        <th>Nombre artístico</th>
        <th>Nacionalidad</th>
        <th>Imagen artista</th>
        <th>Imagen noticia</th>
        <th>Acciones</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['Id']; ?></td>
        <td><?php echo htmlspecialchars($row['Nombre_Artistico']); ?></td>
        <td><?php echo htmlspecialchars($row['Nacionalidad']); ?></td>

        <!-- Imagen Artista -->
<td>
  <?php $imgA = imgSrc($row['Imagen'] ?? ''); ?>
  <?php if ($imgA): ?>
      <img class="img-admin" src="<?php echo htmlspecialchars($imgA); ?>" alt="Artista">
  <?php else: ?>
      <span class="sin-img">Sin imagen</span>
  <?php endif; ?>
</td>

<!-- Imagen Noticia -->
<td>
  <?php $imgN = imgSrc($row['Imagen_Not'] ?? ''); ?>
  <?php if ($imgN): ?>
      <img class="img-admin" src="<?php echo htmlspecialchars($imgN); ?>" alt="Noticia">
  <?php else: ?>
      <span class="sin-img">Sin imagen</span>
  <?php endif; ?>
</td>

        <td class="acciones">
            <a class="btn-editar" href="Editar_item.php?id=<?php echo $row['Id']; ?>">Editar</a>
            <a class="btn-eliminar" href="Eliminar_item.php?id=<?php echo $row['Id']; ?>" onclick="return confirm('¿Eliminar artista?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<br>
<a class="btn-volver" href="./index.php">Volver</a>

</body>
</html>