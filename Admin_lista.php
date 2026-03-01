<?php
session_start();
require_once "Conexion.php";

$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));
if (!$esAdmin || !$hayUser) {
  header("Location: error.php");
  exit();
}

/**
 * Normaliza rutas para imágenes guardadas en BD.
 * Acepta:
 *  - "./Img/xxx.jpg"
 *  - "Img/xxx.jpg"
 *  - "xxx.jpg"
 * Devuelve:
 *  - "Img/xxx.jpg"
 */
function normalizarRuta($valor)
{
  if (empty($valor))
    return null;

  $valor = trim($valor);

  // quitar "./" inicial
  $valor = preg_replace('#^\./#', '', $valor); // "./Img/x" pasa a "Img/x"

  // si ya empieza con "Img/" lo dejamos
  if (preg_match('#^Img/#', $valor)) {
    return $valor;
  }

  // si viene con barras invertidas (Windows) normalizamos
  $valor = str_replace('\\', '/', $valor);

  // si ya trae otra carpeta, lo dejamos como viene
  if (strpos($valor, '/') !== false) {
    return $valor;
  }

  // si es solo el nombre del archivo  lo buscamos en Img/
  return "Img/" . $valor;
}

function imgExiste($src)
{
  if (!$src)
    return false;
  return file_exists(__DIR__ . "/" . $src);
}

// Busqueda
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
  $like = "%{$q}%";
  $sql = "SELECT * FROM artista
            WHERE Nombre_Artistico LIKE ?
               OR Nombre LIKE ?
               OR Apellido LIKE ?
               OR Nacionalidad LIKE ?
            ORDER BY Id DESC";
  $stmt = $conn->prepare($sql);
  if (!$stmt)
    die("Error prepare: " . $conn->error);

  $stmt->bind_param("ssss", $like, $like, $like, $like);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = $conn->query("SELECT * FROM artista ORDER BY Id DESC");
}

if (!$result)
  die("Error consulta: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel administrador - Artistas</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="admin-wrap">
    <h2 class="admin-title">Panel de administración (Artistas)</h2>

    <!-- Buscador -->
    <form class="buscador" method="GET" action="Admin_lista.php">
      <input class="buscador-input" type="search" name="q"
        placeholder="Buscar artista por nombre, artístico, apellido o nacionalidad…"
        value="<?php echo htmlspecialchars($q); ?>">

      <button class="buscador-btn" type="submit">Buscar</button>

      <?php if ($q !== ''): ?>
        <a class="buscador-clear" href="Admin_lista.php">Limpiar</a>
      <?php endif; ?>
    </form>

    <a class="btn-agregar" href="Agregar_item.php">+ Agregar artista</a>

    <!--  wrapper para que la tabla sea responsive (scroll en mobile) -->
    <div class="tabla-wrap">
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
          <?php
          $imgA = normalizarRuta($row['Imagen'] ?? '');
          $imgN = normalizarRuta($row['Imagen_Not'] ?? '');
          $okA = $imgA && imgExiste($imgA);
          $okN = $imgN && imgExiste($imgN);
          ?>
          <tr>
            <td><?php echo (int) $row['Id']; ?></td>
            <td><?php echo htmlspecialchars($row['Nombre_Artistico'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($row['Nacionalidad'] ?? ''); ?></td>

            <td>
              <?php if ($okA): ?>
                <img class="img-admin" src="<?php echo htmlspecialchars($imgA); ?>" alt="Artista">
              <?php else: ?>
                <span class="sin-img">Sin imagen</span>
              <?php endif; ?>
            </td>

            <td>
              <?php if ($okN): ?>
                <img class="img-admin" src="<?php echo htmlspecialchars($imgN); ?>" alt="Noticia">
              <?php else: ?>
                <span class="sin-img">Sin imagen</span>
              <?php endif; ?>
            </td>

            <td class="acciones">
              <a class="btn-editar" href="Editar_item.php?id=<?php echo (int) $row['Id']; ?>">Editar</a>
              <a class="btn-eliminar" href="Eliminar_item.php?id=<?php echo (int) $row['Id']; ?>"
                onclick="return confirm('¿Eliminar artista?')">Eliminar</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <a class="btn-volver" href="./index.php">Volver</a>
  </div>
</body>

</html>