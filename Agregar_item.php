<?php
session_start();
require_once "Conexion.php";

$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));
if (!$esAdmin || !$hayUser) {
  header("Location: error.php");
  exit();
}

function subirImagen($inputName, $carpetaFisica = "Img/")
{
  if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
    return null;
  }

  $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
  $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
  if (!in_array($ext, $permitidas)) {
    return null;
  }

  if (!is_dir($carpetaFisica)) {
    mkdir($carpetaFisica, 0777, true);
  }

  $nuevoNombre = uniqid("img_", true) . "." . $ext;
  $destino = $carpetaFisica . $nuevoNombre;

  if (!move_uploaded_file($_FILES[$inputName]['tmp_name'], $destino)) {
    return null;
  }

  //  Guardamos en BD:
  // "./Img/archivo.jpg"
  return "./Img/" . $nuevoNombre;
}

$error = "";

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

  $Imagen = subirImagen('Imagen');
  $Imagen_Not = subirImagen('Imagen_Not');

  if ($error === "") {
    $sql = "INSERT INTO artista
            (Inicio_Actividad, Fecha_Nacimiento, Nombre, Apellido, Nombre_Artistico, Nombre_Artistico_Anterior, Nacionalidad, Fin_Actividad, Imagen, Descripcion, Noticia, Imagen_Not)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt)
      die("Error prepare: " . $conn->error);

    $stmt->bind_param(
      "ssssssssssss",
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
      $Imagen_Not
    );

    if ($stmt->execute()) {
      header("Location: Admin_lista.php");
      exit;
    } else {
      $error = "Error al guardar: " . $stmt->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar artista</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>

  <div class="admin-wrap">
    <h2 class="admin-title">Agregar artista</h2>

    <?php if (!empty($error)): ?>
      <div class="alerta"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form class="form-admin" method="POST" enctype="multipart/form-data">
      <div class="form-row">
        <label>Nombre:</label>
        <input type="text" name="Nombre">
      </div>

      <div class="form-row">
        <label>Apellido:</label>
        <input type="text" name="Apellido">
      </div>

      <div class="form-row">
        <label>Nombre artístico: (obligatorio)</label>
        <input type="text" name="Nombre_Artistico" required>
      </div>

      <div class="form-row">
        <label>Nacionalidad:</label>
        <input type="text" name="Nacionalidad">
      </div>

      <div class="form-row">
        <label>Inicio actividad:</label>
        <input type="date" name="Inicio_Actividad">
      </div>

      <div class="form-row">
        <label>Descripción:</label>
        <textarea name="Descripcion" required></textarea>
      </div>

      <div class="form-row">
        <label>Noticia:</label>
        <textarea name="Noticia" required></textarea>
      </div>

      <div class="form-row">
        <label>Imagen artista: (formato:.jpg,.jpeg,.png,.webp)</label>
        <input type="file" name="Imagen" accept=".jpg,.jpeg,.png,.webp" required>
      </div>

      <div class="form-row">
        <label>Imagen noticia: (formato:.jpg,.jpeg,.png,.webp)</label>
        <input type="file" name="Imagen_Not" accept=".jpg,.jpeg,.png,.webp" required>
      </div>

      <button class="btn-guardar" type="submit">Guardar</button>
    </form>

    <a class="btn-volver" href="Admin_lista.php">Volver</a>
  </div>

</body>

</html>