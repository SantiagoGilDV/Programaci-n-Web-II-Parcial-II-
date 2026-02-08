<?php
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$id = $_GET['id'];
$item = $conn->query("SELECT * FROM lista WHERE id=$id")->fetch_assoc();

if ($_POST) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE lista SET titulo=?, descripcion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $titulo, $descripcion, $id);
    $stmt->execute();

    header("Location: admin_lista.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar</title>
</head>
<body>

<h2>Editar item</h2>

<form method="POST">
    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?php echo $item['titulo']; ?>"><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion"><?php echo $item['descripcion']; ?></textarea><br><br>

    <button type="submit">Guardar cambios</button>
</form>

<a href="admin_lista.php">Volver</a>

</body>
</html>
