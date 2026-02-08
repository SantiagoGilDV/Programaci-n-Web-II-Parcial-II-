<?php
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['Correo'];

    $sql = "UPDATE usuario SET Correo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $correo, $id);

    if ($stmt->execute()) {
        $mensaje = "Datos actualizados";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar perfil</title>
</head>
<body>

<h2>Editar perfil</h2>

<?php if ($mensaje != ""): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Correo:</label>
    <input type="email" name="Correo" required>
    <button type="submit">Guardar</button>
</form>

<a href="bienvenida.php">Volver</a>

</body>
</html>
