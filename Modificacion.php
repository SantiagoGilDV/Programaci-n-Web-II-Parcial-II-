<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['Nombre_Usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$mensaje = "";

/* =========================
   1️⃣ OBTENER DATOS ACTUALES
========================= */

$sql = "SELECT Nombre, Apellido, Nombre_Usuario, Correo 
        FROM usuario WHERE Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario_actual = $result->fetch_assoc();

/* =========================
   2️⃣ ACTUALIZAR SI ENVÍA FORM
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['Nombre'];
    $apellido = $_POST['Apellido'];
    $usuario = $_POST['Nombre_Usuario'];
    $correo = $_POST['Correo'];

    $update = "UPDATE usuario 
               SET Nombre = ?, 
                   Apellido = ?, 
                   Nombre_Usuario = ?, 
                   Correo = ?
               WHERE Id = ?";

    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssi", $nombre, $apellido, $usuario, $correo, $id);

    if ($stmt->execute()) {

        // Actualizar sesión si cambió el usuario
        $_SESSION['Nombre_Usuario'] = $usuario;

        $mensaje = "Datos actualizados correctamente";

        // Refrescar datos mostrados
        $usuario_actual['Nombre'] = $nombre;
        $usuario_actual['Apellido'] = $apellido;
        $usuario_actual['Nombre_Usuario'] = $usuario;
        $usuario_actual['Correo'] = $correo;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar perfil</title>
<link rel="stylesheet" href="./css/modificacion.css">

</head>
<body>

<div class="form-container">

<h2>Editar Perfil</h2>

<?php if ($mensaje != ""): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
<?php endif; ?>

<form method="POST">

    <label>Nombre:</label>
    <input type="text" name="Nombre"
        value="<?php echo htmlspecialchars($usuario_actual['Nombre']); ?>" required>

    <label>Apellido:</label>
    <input type="text" name="Apellido"
        value="<?php echo htmlspecialchars($usuario_actual['Apellido']); ?>" required>

    <label>Usuario:</label>
    <input type="text" name="Nombre_Usuario"
        value="<?php echo htmlspecialchars($usuario_actual['Nombre_Usuario']); ?>" required>

    <label>Correo:</label>
    <input type="email" name="Correo"
        value="<?php echo htmlspecialchars($usuario_actual['Correo']); ?>" required>

    <button type="submit">Guardar cambios</button>

</form>

<a href="index.php" class="volver">Volver</a>

</div>

</body>
</html>