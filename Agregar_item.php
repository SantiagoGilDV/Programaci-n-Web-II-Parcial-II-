<?php
session_start();
require_once "Conexion.php";

$esAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');
$hayUser = (isset($_SESSION['Nombre_Usuario']) || isset($_SESSION['usuario']));
if (!$esAdmin || !$hayUser) die("Acceso denegado");

function subirImagen($inputName, $carpeta = "Img/") {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;

    $nombre = basename($_FILES[$inputName]['name']);
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

    
    $permitidas = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $permitidas)) return null;

    $nuevoNombre = uniqid("img_", true) . "." . $ext;
    move_uploaded_file($_FILES[$inputName]['tmp_name'], $carpeta . $nuevoNombre);

    return $nuevoNombre;
}

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

    $sql = "INSERT INTO artista
        (Inicio_Actividad, Fecha_Nacimiento, Nombre, Apellido, Nombre_Artistico, Nombre_Artistico_Anterior, Nacionalidad, Fin_Actividad, Imagen, Descripcion, Noticia, Imagen_Not)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Error prepare: " . $conn->error);

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
        echo "Error al guardar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar artista</title>
  <link rel="stylesheet" href="./css/admin.css">
   <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-wrap">
  <h2 class="admin-title">Agregar artista</h2>

  <form class="form-admin" method="POST" enctype="multipart/form-data">
    <div class="form-row">
      <label>Nombre (obligatorio):</label>
      <input type="text" name="Nombre" required>
    </div>

    <div class="form-row">
      <label>Apellido:</label>
      <input type="text" name="Apellido">
    </div>

    <div class="form-row">
      <label>Nombre artístico:</label>
      <input type="text" name="Nombre_Artistico">
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
      <textarea name="Descripcion"></textarea>
    </div>

    <div class="form-row">
      <label>Noticia:</label>
      <textarea name="Noticia"></textarea>
    </div>

    <div class="form-row">
      <label>Imagen artista:</label>
      <input type="file" name="Imagen" accept=".jpg,.jpeg,.png,.webp">
    </div>

    <div class="form-row">
      <label>Imagen noticia:</label>
      <input type="file" name="Imagen_Not" accept=".jpg,.jpeg,.png,.webp">
    </div>

    <button class="btn-guardar" type="submit">Guardar</button>
  </form>

  <a class="btn-volver" href="Admin_lista.php">Volver</a>
</div>

</body>
</html>