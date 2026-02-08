<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

if ($_POST) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    $imagen = $_FILES['imagen']['name'];
    move_uploaded_file($_FILES['imagen']['tmp_name'], "imagenes/" . $imagen);

    $sql = "INSERT INTO lista (titulo, descripcion, imagen)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $titulo, $descripcion, $imagen);
    $stmt->execute();

    header("Location: admin_lista.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar</title>
</head>
<body>

<h2>Agregar item</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion"></textarea><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" required><br><br>

    <button type="submit">Guardar</button>
</form>

<a href="admin_lista.php">Volver</a>

</body>
</html>
