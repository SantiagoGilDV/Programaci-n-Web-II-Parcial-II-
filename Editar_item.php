<?php
session_start();
require_once "Conexion.php";

$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));
if (!$esAdmin || !$hayUser) {
  header("Location: error.php");
  exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
  die("ID inválido");

function normalizarRuta($valor)
{
  if (empty($valor))
    return null;
  $valor = trim($valor);
  // saca "./" al inicio si existe
  $valor = preg_replace('#^\./#', '', $valor);
  return $valor;
}

function subirImagen($inputName, $carpeta = "Img/")
{
  if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK)
    return null;

  $nombre = basename($_FILES[$inputName]['name']);
  $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

  $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
  if (!in_array($ext, $permitidas))
    return null;

  $nuevoNombre = uniqid("img_", true) . "." . $ext;

  if (!is_dir($carpeta)) {
    // por si no existe la carpeta
    mkdir($carpeta, 0777, true);
  }

  move_uploaded_file($_FILES[$inputName]['tmp_name'], $carpeta . $nuevoNombre);
  return "./Img/" . $nuevoNombre;
}

// Traer artista actual
$stmt = $conn->prepare("SELECT * FROM artista WHERE Id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$art = $stmt->get_result()->fetch_assoc();
if (!$art)
  die("Artista no encontrado");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $Nombre = $_POST['Nombre'] ?? '';
  $Apellido = $_POST['Apellido'] ?? null;
  $Nombre_Artistico = $_POST['Nombre_Artistico'] ?? null;
  $Nombre_Artistico_Anterior = $_POST['Nombre_Artistico_Anterior'] ?? null;
  $Nacionalidad = $_POST['Nacionalidad'] ?? null;

  $Inicio_Actividad = $_POST['Inicio_Actividad'] ?? null;
  $Fecha_Nacimiento = $_POST['Fecha_Nacimiento'] ?? null;
  $Fin_Actividad = $_POST['Fin_Actividad'] ?? null;

  $Descripcion = $_POST['Descripcion'] ?? null;
  $Noticia = $_POST['Noticia'] ?? null;

  $nuevaImagen = subirImagen('Imagen');
  $nuevaImagenNot = subirImagen('Imagen_Not');

  $Imagen = $nuevaImagen ?: ($art['Imagen'] ?? null);
  $Imagen_Not = $nuevaImagenNot ?: ($art['Imagen_Not'] ?? null);

  $sql = "UPDATE artista SET
        Inicio_Actividad=?,
        Fecha_Nacimiento=?,
        Nombre=?,
        Apellido=?,
        Nombre_Artistico=?,
        Nombre_Artistico_Anterior=?,
        Nacionalidad=?,
        Fin_Actividad=?,
        Imagen=?,
        Descripcion=?,
        Noticia=?,
        Imagen_Not=?
        WHERE Id=?";

  $up = $conn->prepare($sql);
  if (!$up)
    die("Error prepare: " . $conn->error);

  $up->bind_param(
    "ssssssssssssi",
    $Inicio_Actividad,
    $Fecha_Nacimiento,
    $Nombre,
    $Apellido,
    $Nombre_Artistico,
    $Nombre_Artistico_Anterior,
    $Nacionalidad,
    $Fin_Actividad,
    $Imagen,
    $Descripcion,
    $Noticia,
    $Imagen_Not,
    $id
  );

  if ($up->execute()) {
    header("Location: Admin_lista.php");
    exit;
  } else {
    $error = "Error al actualizar: " . $up->error;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar artista</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>

  <div class="admin-wrap">
    <h2 class="admin-title">Editar artista (ID <?php echo (int) $art['Id']; ?>)</h2>

    <?php if (!empty($error)): ?>
      <p style="text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form class="form-admin" method="POST" enctype="multipart/form-data">

      <div class="form-row">
        <label>Nombre (obligatorio):</label>
        <input type="text" name="Nombre" required value="<?php echo htmlspecialchars($art['Nombre'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Apellido:</label>
        <input type="text" name="Apellido" value="<?php echo htmlspecialchars($art['Apellido'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Nombre artístico:</label>
        <input type="text" name="Nombre_Artistico"
          value="<?php echo htmlspecialchars($art['Nombre_Artistico'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Nombre artístico anterior:</label>
        <input type="text" name="Nombre_Artistico_Anterior"
          value="<?php echo htmlspecialchars($art['Nombre_Artistico_Anterior'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Nacionalidad:</label>
        <input type="text" name="Nacionalidad" value="<?php echo htmlspecialchars($art['Nacionalidad'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Inicio actividad:</label>
        <input type="date" name="Inicio_Actividad"
          value="<?php echo htmlspecialchars($art['Inicio_Actividad'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Fecha nacimiento:</label>
        <input type="date" name="Fecha_Nacimiento"
          value="<?php echo htmlspecialchars($art['Fecha_Nacimiento'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Fin actividad:</label>
        <input type="date" name="Fin_Actividad" value="<?php echo htmlspecialchars($art['Fin_Actividad'] ?? ''); ?>">
      </div>

      <div class="form-row">
        <label>Descripción:</label>
        <textarea name="Descripcion" rows="5"><?php echo htmlspecialchars($art['Descripcion'] ?? ''); ?></textarea>
      </div>

      <div class="form-row">
        <label>Noticia:</label>
        <textarea name="Noticia" rows="5"><?php echo htmlspecialchars($art['Noticia'] ?? ''); ?></textarea>
      </div>

      <!-- IMAGEN ARTISTA -->
      <div class="form-row">
        <label>Imagen artista (si no subís, queda la actual):</label>
        <div class="preview">
          <?php $srcA = normalizarRuta($art['Imagen'] ?? null); ?>
          <?php if (!empty($srcA)): ?>
            <img src="<?php echo htmlspecialchars($srcA); ?>" alt="Actual">
          <?php else: ?>
            <span class="sin-img">Sin imagen</span>
          <?php endif; ?>
          <input type="file" name="Imagen" accept=".jpg,.jpeg,.png,.webp">
        </div>
      </div>

      <!-- IMAGEN NOTICIA -->
      <div class="form-row">
        <label>Imagen noticia (si no subís, queda la actual):</label>
        <div class="preview">
          <?php $srcN = normalizarRuta($art['Imagen_Not'] ?? null); ?>
          <?php if (!empty($srcN)): ?>
            <img src="<?php echo htmlspecialchars($srcN); ?>" alt="Actual">
          <?php else: ?>
            <span class="sin-img">Sin imagen</span>
          <?php endif; ?>
          <input type="file" name="Imagen_Not" accept=".jpg,.jpeg,.png,.webp">
        </div>
      </div>

      <button class="btn-guardar" type="submit">Guardar cambios</button>
    </form>

    <a class="btn-volver" href="Admin_lista.php">Volver</a>
  </div>

</body>

</html>